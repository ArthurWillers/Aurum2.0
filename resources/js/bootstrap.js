import axios from 'axios';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import { initFinancialEvolutionChart } from './charts/financialEvolutionChart';
window.axios = axios;
window.Alpine = Alpine;
window.Chart = Chart;
window.initFinancialEvolutionChart = initFinancialEvolutionChart;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Alpine.start();