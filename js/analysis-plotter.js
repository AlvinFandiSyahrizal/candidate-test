'use strict';

class AnalysisPlotter {
    constructor(container) {
        this.container = container;
        this.chart = null;
    }

    _generatePoints(data) {
        const { beam, equation } = data;
        const L1     = beam.primarySpan  || 0;
        const L2     = beam.secondarySpan || 0;
        const totalL = L2 ? L1 + L2 : L1;
        const steps  = 200;
        const points = [];

        for (let i = 0; i <= steps; i++) {
            const x      = (i / steps) * totalL;
            const result = equation(x);
            points.push({ x: parseFloat(x.toFixed(6)), y: parseFloat(result.y.toFixed(6)) });
        }

        // Tambah titik tepat di L1 (kiri dan kanan) untuk shear discontinuity
        if (L2) {
            const rLeft  = equation(L1 - 0.00001);
            const rRight = equation(L1 + 0.00001);
            points.push({ x: L1, y: parseFloat(rLeft.y.toFixed(6)) });
            points.push({ x: L1, y: parseFloat(rRight.y.toFixed(6)) });
            points.sort((a, b) => a.x - b.x || 0);
        }

        return points;
    }

    _getLabel() {
        if (this.container.includes('deflection'))  return 'Deflection (mm)';
        if (this.container.includes('shear'))       return 'Shear Force (kN)';
        if (this.container.includes('bending'))     return 'Bending Moment (kN·m)';
        return 'Value';
    }

    plot(data) {
        const points = this._generatePoints(data);
        const label  = this._getLabel();
        const canvas = document.getElementById(this.container);

        if (!canvas) { console.error('Canvas not found:', this.container); return; }

        // Destroy dulu sebelum bikin baru — fix "Canvas already in use"
        if (this.chart) {
            this.chart.destroy();
            this.chart = null;
        }

        const ctx = canvas.getContext('2d');

        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    label: label,
                    data: points,
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: {
                        target: { value: 0 },
                        above: 'rgba(239,68,68,0.12)',
                        below: 'rgba(150,150,180,0.2)'
                    },
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                animation: false,
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y.toFixed(3)}`
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'linear',
                        title: { display: true, text: 'Position x (m)' },
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#a0aec0' }
                    },
                    y: {
                        title: { display: true, text: label },
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#a0aec0' }
                    }
                }
            }
        });
    }
}