'use strict';

/** ============================ Beam Analysis Data Type ============================ */

class Material {
    constructor(name, properties) {
        this.name = name;
        this.properties = properties;
    }
}

class Beam {
    constructor(primarySpan, secondarySpan, material) {
        this.primarySpan = primarySpan;
        this.secondarySpan = secondarySpan;
        this.material = material;
    }
}

/** ============================ Beam Analysis Class ============================ */

class BeamAnalysis {
    constructor() {
        this.options = { condition: 'simply-supported' };
        this.analyzer = {
            'simply-supported': new BeamAnalysis.analyzer.simplySupported(),
            'two-span-unequal': new BeamAnalysis.analyzer.twoSpanUnequal()
        };
    }

    getDeflection(beam, load, condition) {
        var analyzer = this.analyzer[condition];
        if (analyzer) return { beam, load, equation: analyzer.getDeflectionEquation(beam, load) };
        throw new Error('Invalid condition');
    }

    getBendingMoment(beam, load, condition) {
        var analyzer = this.analyzer[condition];
        if (analyzer) return { beam, load, equation: analyzer.getBendingMomentEquation(beam, load) };
        throw new Error('Invalid condition');
    }

    getShearForce(beam, load, condition) {
        var analyzer = this.analyzer[condition];
        if (analyzer) return { beam, load, equation: analyzer.getShearForceEquation(beam, load) };
        throw new Error('Invalid condition');
    }
}

/** ============================ Beam Analysis Analyzer ============================ */

BeamAnalysis.analyzer = {};

/**
 * Simply Supported Beam — Uniformly Distributed Load
 *
 * Dari Excel Sheet 1 (w=20, L=8, j2=2, EI=3150000000000):
 *   V(x)  = wL/2 - wx           → +80 di x=0, -80 di x=8
 *   M(x)  = wLx/2 - wx²/2       → 0 di ujung, +160 di tengah (positif = sagging)
 *   δ(x)  = -j2*(w/24EI)*x*(L³-2Lx²+x³)*1000   → negatif (ke bawah), mm
 */
BeamAnalysis.analyzer.simplySupported = class {
    constructor() {}

    getShearForceEquation(beam, load) {
        const w = load;
        const L = beam.primarySpan;
        return function (x) {
            return { x, y: (w * L / 2) - (w * x) };
        };
    }

    getBendingMomentEquation(beam, load) {
        const w = load;
        const L = beam.primarySpan;
        return function (x) {
            // Positif ke atas (sagging) sesuai expected result
            return { x, y: (w * L * x / 2) - (w * x * x / 2) };
        };
    }

    getDeflectionEquation(beam, load) {
        const w  = load;
        const L  = beam.primarySpan;
        const EI = beam.material.properties.EI / 1e9; // N·mm² → kN·m²
        const j2 = beam.material.properties.j2 || 2;
        return function (x) {
            const delta = -j2 * (w / (24 * EI)) * x * (Math.pow(L, 3) - 2 * L * x * x + Math.pow(x, 3)) * 1000;
            return { x, y: delta };
        };
    }
};

/**
 * Two-Span Unequal Beam — Equal UDL
 *
 * Three-Moment Equation:
 *   M1 = -w(L1³ + L2³) / (8(L1 + L2))
 *
 *   R1 = wL1/2 - M1/L1
 *   R3 = wL2/2 - M1/L2
 *   R2 = w(L1+L2) - R1 - R3
 */
BeamAnalysis.analyzer.twoSpanUnequal = class {
    constructor() {}

    _reactions(beam, load) {
        const w  = load;
        const L1 = beam.primarySpan;
        const L2 = beam.secondarySpan;
        const M1 = -w * (Math.pow(L1, 3) + Math.pow(L2, 3)) / (8 * (L1 + L2));
        const R1 = (w * L1 / 2) + (M1 / L1);
        const R3 = (w * L2 / 2) + (M1 / L2);
        const R2 = w * (L1 + L2) - R1 - R3;
        return { M1, R1, R2, R3 };
    }

    getShearForceEquation(beam, load) {
        const w  = load;
        const L1 = beam.primarySpan;
        const { R1, R2 } = this._reactions(beam, load);
        return function (x) {
            let V;
            if (x <= L1) {
                V = R1 - w * x;
            } else {
                V = R1 + R2 - w * x;
            }
            return { x, y: V };
        };
    }

    getBendingMomentEquation(beam, load) {
        const w  = load;
        const L1 = beam.primarySpan;
        const { R1, R2 } = this._reactions(beam, load);
        return function (x) {
            let M;
            if (x <= L1) {
                M = R1 * x - (w * x * x / 2);
            } else {
                M = R1 * x + R2 * (x - L1) - (w * x * x / 2);
            }
            return { x, y: M };
        };
    }

    getDeflectionEquation(beam, load) {
        const w  = load;
        const L1 = beam.primarySpan;
        const L2 = beam.secondarySpan;
        const EI = beam.material.properties.EI / 1e9;
        const j2 = beam.material.properties.j2 || 2;
        const { M1 } = this._reactions(beam, load);

        return function (x) {
            let delta;
            if (x <= L1) {
                const d_udl = (w / (24 * EI)) * x * (Math.pow(L1, 3) - 2 * L1 * x * x + Math.pow(x, 3));
                const d_m1  = (M1 / (6 * EI * L1)) * x * (L1 * L1 - x * x);
                delta = -j2 * (d_udl + d_m1) * 1000;
            } else {
                const x2    = (L1 + L2) - x;
                const d_udl = (w / (24 * EI)) * x2 * (Math.pow(L2, 3) - 2 * L2 * x2 * x2 + Math.pow(x2, 3));
                const d_m1  = (M1 / (6 * EI * L2)) * x2 * (L2 * L2 - x2 * x2);
                delta = -j2 * (d_udl + d_m1) * 1000;
            }
            return { x, y: delta };
        };
    }
};