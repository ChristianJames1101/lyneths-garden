<?php
include '../db.php';

$result = $conn->query("SELECT SUM(total) as revenue FROM completed_orders");
$row = $result->fetch_assoc();
$totalSales = $row ? $row['revenue'] : 0;

$totalOrdersQuery = $conn->query("SELECT 
    (SELECT COUNT(*) FROM orders) + 
    (SELECT COUNT(*) FROM completed_orders) AS total_count");
$totalOrders = $totalOrdersQuery->fetch_assoc()['total_count'];

$completedOrders = $conn->query("SELECT COUNT(*) as count FROM completed_orders")->fetch_assoc()['count'];
$pendingOrders   = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'")->fetch_assoc()['count'];

$cancellationCount = $conn->query("SELECT COUNT(*) as count FROM cancellation_requests")->fetch_assoc()['count'];

$cancellationRequests = $conn->query("SELECT cr.*, o.name FROM cancellation_requests cr LEFT JOIN orders o ON cr.order_id = o.id ORDER BY cr.request_date DESC");


$revenueData = [];
$revenueQuery = $conn->query("
    SELECT DATE(completed_date) as date, SUM(total) as total
    FROM completed_orders
    GROUP BY DATE(completed_date)
    ORDER BY DATE(completed_date)
");
while ($r = $revenueQuery->fetch_assoc()) {
    $revenueData[] = ['date'=>$r['date'],'total'=>$r['total']];
}

$completedOrdersList = $conn->query("SELECT * FROM completed_orders ORDER BY completed_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Dashboard</title>
  <style>
    body { margin:0; }
    .dashboard {
      font-family: 'Segoe UI', sans-serif;
      padding: 30px;
      background: #f1f1f1;
    }
    .dashboard h2 { color: #2d572c; margin-bottom:25px; font-size:28px; }
    .stats { display:flex; flex-wrap:wrap; gap:20px; margin-bottom:40px; }
    .card {
      flex:1; min-width:200px; background:white; border-radius:12px;
      padding:20px; box-shadow:0 4px 12px rgba(0,0,0,0.06);
    }
    .chart-container {
      margin-bottom:30px; background:white; padding:20px;
      border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.05);
    }
    .controls {
      display:flex; flex-wrap:wrap; justify-content:space-between;
      align-items:center; margin-bottom:20px; gap:15px;
    }
    .filters, .search, .actions {
      display:flex; flex-wrap:wrap; align-items:center; gap:10px;
    }
    .filters input, .search input { padding:6px; font-size:14px; }
    table {
      width:100%; border-collapse:collapse; background:white;
      border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05);
    }
    th, td {
      padding:14px 16px; text-align:left; border-bottom:1px solid #f0f0f0;
      font-size:14px;
    }
    th { background:#e9f5e9; color:#2d572c; }
    tr:hover { background:#f7f7f7; }
    .print-btn, .download-btn {
      padding:6px 12px; border:none; border-radius:5px; cursor:pointer;
      font-size:13px; margin-right:6px;
    }
    .print-btn { background:#4caf50; color:white; }
    .download-btn { background:#2196f3; color:white; }
    th.sortable { cursor:pointer; }
    th.sortable:after { content:' ⇅'; font-size:.8em; color:#888; }
    @media (max-width:768px) {
      .controls { flex-direction:column; align-items:stretch; }
    }
  </style>
</head>
<body>
<section class="dashboard">
  <h2>Sales Reports</h2>
  <div class="stats">
    <div class="card" style="border-left:5px solid #4caf50;">
      <h3>Total Revenue</h3><p>₱<?= number_format($totalSales,2) ?></p>
    </div>
    <div class="card" style="border-left:5px solid #ffca28;">
      <h3>Total Orders</h3><p><?= $totalOrders ?></p>
    </div>
    <div class="card" style="border-left:5px solid #2196f3;">
      <h3>Completed Orders</h3><p><?= $completedOrders ?></p>
    </div>
    <div class="card" style="border-left:5px solid #f44336;">
      <h3>Pending Orders</h3><p><?= $pendingOrders ?></p>
    </div>
    <div class="card clickable" id="cancelCard" style="border-left:5px solid #9c27b0;">
      <h3>Cancellation Requests</h3><p><?= $cancellationCount ?></p>
    </div>
  </div>

  <div class="chart-container">
    <h3 style="margin-bottom:15px;">Revenue Trend</h3>
    <canvas id="revenueChart" style="width:100%; height:300px;"></canvas>
  </div>

  <h2 style="margin-bottom:15px;">All Completed Orders</h2>
  <div class="controls">
    <div class="filters">
      <label>From <input type="date" id="startDate"></label>
      <label>To   <input type="date" id="endDate"></label>
      <button onclick="applyFilter()">Apply</button>
      <button onclick="resetFilter()">Reset</button>
    </div>
    <div class="search">
      <input type="text" id="searchInput" placeholder="Search…">
      <button onclick="applySearch()">Search</button>
      <button onclick="clearSearch()">Clear</button>
    </div>
    <div class="actions">
      <button onclick="printTable()" class="print-btn">Print Report</button>
      <button onclick="downloadCSV()" class="download-btn">Download CSV</button>
    </div>
  </div>

  <table id="ordersTable">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Contact</th><th>Address</th>
        <th>Product</th><th>Qty</th><th>Total</th><th>Status</th>
        <th class="sortable" onclick="sortTableByDate()">Completed Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($o = $completedOrdersList->fetch_assoc()): ?>
      <tr
        data-id="<?= $o['id'] ?>"
        data-name="<?= htmlspecialchars($o['name'],ENT_QUOTES) ?>"
        data-contact="<?= htmlspecialchars($o['contact_number'],ENT_QUOTES) ?>"
        data-address="<?= htmlspecialchars($o['address'],ENT_QUOTES) ?>"
        data-product="<?= htmlspecialchars($o['product_name'],ENT_QUOTES) ?>"
        data-qty="<?= $o['quantity'] ?>"
        data-total="<?= number_format($o['total'],2) ?>"
        data-status="<?= $o['status'] ?>"
        data-date="<?= date("F j, Y g:i A",strtotime($o['completed_date'])) ?>"
      >
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['name']) ?></td>
        <td><?= htmlspecialchars($o['contact_number']) ?></td>
        <td><?= htmlspecialchars($o['address']) ?></td>
        <td><?= htmlspecialchars($o['product_name']) ?></td>
        <td><?= $o['quantity'] ?></td>
        <td>₱<?= number_format($o['total'],2) ?></td>
        <td><?= ucfirst($o['status']) ?></td>
        <td class="date-cell"><?= date("F j, Y g:i A",strtotime($o['completed_date'])) ?></td>
        <td>
          <button class="print-btn"    onclick="printReceipt(this)">Print</button>
          <button class="download-btn" onclick="downloadReceipt(this)">Download</button>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = <?= json_encode(array_column($revenueData,'date')) ?>;
  const vals   = <?= json_encode(array_column($revenueData,'total')) ?>;
  new Chart(
    document.getElementById('revenueChart').getContext('2d'),
    {
      type:'line',
      data:{ labels, datasets:[{ label:'Daily Revenue (₱)', data:vals, backgroundColor:'rgba(63,166,75,0.2)', borderColor:'#3fa64b', borderWidth:2, pointRadius:4, tension:0.3 }]},
      options:{ responsive:true, scales:{ y:{ beginAtZero:true } } }
    }
  );

  function applyFilter(){
    const s = new Date(document.getElementById('startDate').value);
    const e = new Date(document.getElementById('endDate').value);
    document.querySelectorAll('#ordersTable tbody tr').forEach(tr=>{
      const d = new Date(tr.querySelector('.date-cell').textContent);
      tr.style.display = ((!isNaN(s) && d<s) || (!isNaN(e) && d>e)) ? 'none' : '';
    });
  }
  function resetFilter(){
    document.getElementById('startDate').value='';
    document.getElementById('endDate').value='';
    document.querySelectorAll('#ordersTable tbody tr').forEach(tr=>tr.style.display='');
  }

  function applySearch(){
    const term = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#ordersTable tbody tr').forEach(tr=>{
      tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
  }
  function clearSearch(){
    document.getElementById('searchInput').value='';
    document.querySelectorAll('#ordersTable tbody tr').forEach(tr=>tr.style.display='');
  }

  let asc=false;
  function sortTableByDate(){
    const tbody = document.querySelector('#ordersTable tbody');
    Array.from(tbody.children)
      .filter(r=>r.style.display!=='none')
      .sort((a,b)=>{
        const da=new Date(a.querySelector('.date-cell').textContent),
              db=new Date(b.querySelector('.date-cell').textContent);
        return asc ? da-db : db-da;
      })
      .forEach(r=>tbody.appendChild(r));
    asc=!asc;
  }

  function printTable(){
  const theadClone = document.querySelector('#ordersTable thead').cloneNode(true);
  theadClone.querySelector('tr').lastElementChild.remove(); 

  const rows = Array.from(document.querySelectorAll('#ordersTable tbody tr'))
    .filter(r => r.style.display !== 'none')
    .map(r => {
      const cells = Array.from(r.children);
      cells.pop();
      return `<tr>${cells.map(c => c.outerHTML).join('')}</tr>`;
    }).join('');

  const html = `
    <html><head><title>Report</title>
      <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #f0f0f0; }
      </style>
    </head><body>
      <h2>Completed Orders Report</h2>
      <table>
        <thead>${theadClone.innerHTML}</thead>
        <tbody>${rows}</tbody>
      </table>
    </body></html>`;

  const w = window.open('', '_blank', `left=0,top=0,width=${screen.availWidth},height=${screen.availHeight}`);
  w.document.write(html); w.document.close(); w.focus(); w.print(); w.close();
}

  function downloadCSV(){
    const headers = ['ID','Name','Contact','Address','Product','Qty','Total','Status','Completed Date'];
    const rows = Array.from(document.querySelectorAll('#ordersTable tbody tr'))
      .filter(r=>r.style.display!=='none')
      .map(r=>Array.from(r.children).slice(0,9)
                   .map(td=>`"${td.textContent.trim()}"`)
                   .join(','));
    const csv = [headers.join(','), ...rows].join('\n');
    const b = new Blob([csv],{type:'text/csv'}), a=document.createElement('a');
    a.href=URL.createObjectURL(b); a.download='completed_orders.csv';
    document.body.appendChild(a); a.click(); a.remove();
  }

  function printReceipt(btn){
    const tr=btn.closest('tr'), d=tr.dataset;
    const html=`<html><head><title>Receipt #${d.id}</title>
        <style>body{font-family:Arial;padding:20px;}table{width:100%;border-collapse:collapse;margin-top:20px;}
        th,td{border:1px solid #ccc;padding:10px;}th{background:#e9f5e9;}
        </style></head><body>
        <h2>Order Receipt #${d.id}</h2>
        <p><strong>Name:</strong> ${d.name}</p>
        <p><strong>Contact:</strong> ${d.contact}</p>
        <p><strong>Address:</strong> ${d.address}</p>
        <p><strong>Date:</strong> ${d.date}</p>
        <table><thead><tr><th>Product</th><th>Qty</th><th>Total</th><th>Status</th></tr></thead>
        <tbody><tr><td>${d.product}</td><td>${d.qty}</td><td>₱${d.total}</td><td>${d.status}</td></tr>
        </tbody></table></body></html>`;
    const w=window.open('','_blank',`left=0,top=0,width=${screen.availWidth},height=${screen.availHeight}`);
    w.document.write(html); w.document.close(); w.focus(); w.print(); w.close();
  }
  function downloadReceipt(btn){
    const tr=btn.closest('tr'), d=tr.dataset;
    const row = [d.id,d.name,d.contact,d.address,d.product,d.qty,`₱${d.total}`,d.status,d.date]
                  .map(v=>`"${v}"`).join(',');
    const csv = `"ID","Name","Contact","Address","Product","Qty","Total","Status","Date"\n${row}`;
    const b=new Blob([csv],{type:'text/csv'}),a=document.createElement('a');
    a.href=URL.createObjectURL(b); a.download=`receipt_${d.id}.csv`;
    document.body.appendChild(a); a.click(); a.remove();
  }
</script>
</body>
</html>
