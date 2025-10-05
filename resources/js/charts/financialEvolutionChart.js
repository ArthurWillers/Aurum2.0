/**
 * Gráfico de Evolução Financeira
 * Mostra a evolução de receitas, despesas e saldo ao longo do tempo
 */
export function initFinancialEvolutionChart(chartData) {
  const canvas = document.getElementById('financialEvolutionChart');

  if (!canvas || !chartData || chartData.length === 0) {
    console.warn('Canvas ou dados do gráfico não encontrados');
    return;
  }

  const ctx = canvas.getContext('2d');

  // Extrair dados para o gráfico
  const labels = chartData.map(item => item.month);
  const incomes = chartData.map(item => item.incomes);
  const expenses = chartData.map(item => item.expenses);
  const balances = chartData.map(item => item.balance);

  // Criar o gráfico
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Receitas',
          data: incomes,
          borderColor: 'rgba(34, 197, 94, 1)',
          backgroundColor: (context) => {
            const ctx = context.chart.ctx;
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(34, 197, 94, 0.5)');
            gradient.addColorStop(1, 'rgba(34, 197, 94, 0.05)');
            return gradient;
          },
          borderWidth: 2.5,
          pointBackgroundColor: 'rgba(34, 197, 94, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
          pointHoverBorderWidth: 3,
          tension: 0.4,
          fill: true,
          order: 2
        },
        {
          label: 'Despesas',
          data: expenses,
          borderColor: 'rgba(239, 68, 68, 1)',
          backgroundColor: (context) => {
            const ctx = context.chart.ctx;
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(239, 68, 68, 0.5)');
            gradient.addColorStop(1, 'rgba(239, 68, 68, 0.05)');
            return gradient;
          },
          borderWidth: 2.5,
          pointBackgroundColor: 'rgba(239, 68, 68, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
          pointHoverBorderWidth: 3,
          tension: 0.4,
          fill: true,
          order: 3
        },
        {
          label: 'Saldo',
          data: balances,
          borderColor: 'rgba(59, 130, 246, 1)',
          backgroundColor: 'transparent',
          borderWidth: 3.5,
          borderDash: [0],
          pointBackgroundColor: 'rgba(59, 130, 246, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 3,
          pointRadius: 6,
          pointHoverRadius: 9,
          pointHoverBorderWidth: 4,
          tension: 0.4,
          fill: false,
          order: 1,
          shadowOffsetX: 3,
          shadowOffsetY: 3,
          shadowBlur: 10,
          shadowColor: 'rgba(59, 130, 246, 0.3)'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          align: 'center',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            boxWidth: 8,
            boxHeight: 8,
            padding: 15,
            font: {
              size: 12,
              family: "'Inter', sans-serif",
              weight: '600'
            },
            color: '#333'
          }
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          titleFont: {
            size: 14,
            family: "'Inter', sans-serif"
          },
          bodyFont: {
            size: 13,
            family: "'Inter', sans-serif"
          },
          padding: 12,
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.parsed.y !== null) {
                label += new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
                }).format(context.parsed.y);
              }
              return label;
            }
          }
        }
      },
      layout: {
        padding: {
          top: 10,
          right: 15,
          bottom: 10,
          left: 5
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          grid: {
            drawBorder: false,
            color: 'rgba(200, 200, 200, 0.2)',
            lineWidth: 1
          },
          ticks: {
            callback: function (value) {
              // Formatação mais compacta para mobile
              if (value >= 1000) {
                return 'R$ ' + (value / 1000).toFixed(0) + 'k';
              }
              return 'R$ ' + value.toLocaleString('pt-BR');
            },
            maxTicksLimit: 6,
            font: {
              size: 11,
              family: "'Inter', sans-serif",
              weight: '500'
            },
            padding: 10,
            color: '#666'
          }
        },
        x: {
          grid: {
            display: false,
            drawBorder: false
          },
          ticks: {
            font: {
              size: 11,
              family: "'Inter', sans-serif",
              weight: '500'
            },
            padding: 10,
            color: '#666',
            maxRotation: 45,
            minRotation: 0
          }
        }
      },
      interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
      }
    }
  });

  return chart;
}
