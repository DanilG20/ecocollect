document.getElementById('toggle-btn').addEventListener('click', () => {
  const sidebar = document.getElementById('sidebar');
  const main = document.getElementById('main-content');
  sidebar.classList.toggle('closed');
  main.classList.toggle('closed');
});

// Contoh data dummy
const stats = {
  totalWaste: 1234,    // kg
  totalValue: 567890,  // rupiah
  todayTransactions: 15
};

document.getElementById('total-waste').innerText = stats.totalWaste + ' kg';
document.getElementById('total-value').innerText = 'Rp ' + stats.totalValue.toLocaleString();
document.getElementById('today-transactions').innerText = stats.todayTransactions;

// Contoh tabel transaksi
const transactions = [
  { date: '2025-10-01', type: 'Plastik', weight: 5, value: 15000 },
  { date: '2025-10-01', type: 'Kertas', weight: 3, value: 6000 },
  { date: '2025-10-01', type: 'Logam', weight: 1, value: 20000 },
];

const tbody = document.getElementById('transaction-table');
transactions.forEach(tx => {
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${tx.date}</td>
    <td>${tx.type}</td>
    <td>${tx.weight}</td>
    <td>Rp ${tx.value.toLocaleString()}</td>
  `;
  tbody.appendChild(tr);
});

// Contoh grafik menggunakan Chart.js
const ctx1 = document.getElementById('chartWasteByType').getContext('2d');
new Chart(ctx1, {
  type: 'pie',
  data: {
    labels: ['Plastik', 'Kertas', 'Logam'],
    datasets: [{
      data: [5, 3, 1],
      backgroundColor: ['#2da86f', '#4acfac', '#85e0c1']
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});

const ctx2 = document.getElementById('chartMonthly').getContext('2d');
new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [{
      label: 'Kg Sampah',
      data: [100, 150, 130, 170, 180, 200],
      backgroundColor: '#2da86f'
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: { beginAtZero: true }
    }
  }
});
