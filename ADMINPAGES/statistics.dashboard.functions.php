<?php
// Get current year and month or from URL parameters if available
$currentYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('m');

// Function to get monthly transaction data
function getMonthlyTransactions($conn, $year, $month) {
    $query = "SELECT 
                DATE(transaction_date) as date,
                COUNT(*) as total_transactions,
                SUM(quantity * price_at_purchase) as total_sales
              FROM transactions
              WHERE YEAR(transaction_date) = ? AND MONTH(transaction_date) = ?
              GROUP BY DATE(transaction_date)
              ORDER BY date ASC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactionData = [];
    while ($row = $result->fetch_assoc()) {
        $transactionData[$row['date']] = [
            'total_transactions' => $row['total_transactions'],
            'total_sales' => $row['total_sales']
        ];
    }
    
    return $transactionData;
}

// Function to get monthly transaction data by category
function getTransactionsByCategory($conn, $year, $month) {
    $query = "SELECT 
                si.category,
                COUNT(*) as total_transactions,
                SUM(t.quantity * t.price_at_purchase) as total_sales
              FROM transactions t
              JOIN shoppinginventory si ON t.shop_item_id = si.shop_item_id
              WHERE YEAR(t.transaction_date) = ? AND MONTH(t.transaction_date) = ?
              GROUP BY si.category
              ORDER BY total_sales DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categoryData = [];
    while ($row = $result->fetch_assoc()) {
        $categoryData[$row['category']] = [
            'total_transactions' => $row['total_transactions'],
            'total_sales' => $row['total_sales']
        ];
    }
    
    return $categoryData;
}

// Function to get top selling products
function getTopSellingProducts($conn, $year, $month, $limit = 5) {
    $query = "SELECT 
                si.item_name,
                SUM(t.quantity) as total_quantity,
                SUM(t.quantity * t.price_at_purchase) as total_sales
              FROM transactions t
              JOIN shoppinginventory si ON t.shop_item_id = si.shop_item_id
              WHERE YEAR(t.transaction_date) = ? AND MONTH(t.transaction_date) = ?
              GROUP BY t.shop_item_id
              ORDER BY total_sales DESC
              LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $year, $month, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productData = [];
    while ($row = $result->fetch_assoc()) {
        $productData[$row['item_name']] = [
            'total_quantity' => $row['total_quantity'],
            'total_sales' => $row['total_sales']
        ];
    }
    
    return $productData;
}

// Function to get summary statistics for the month
function getMonthlySummary($conn, $year, $month) {
    $query = "SELECT 
                COUNT(DISTINCT transaction_id) as total_transactions,
                SUM(quantity * price_at_purchase) as total_sales,
                COUNT(DISTINCT userid) as unique_customers,
                AVG(quantity * price_at_purchase) as average_transaction_value
              FROM transactions
              WHERE YEAR(transaction_date) = ? AND MONTH(transaction_date) = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return [
            'total_transactions' => $row['total_transactions'] ?? 0,
            'total_sales' => $row['total_sales'] ?? 0,
            'unique_customers' => $row['unique_customers'] ?? 0,
            'average_transaction' => $row['average_transaction_value'] ?? 0
        ];
    }
    
    return [
        'total_transactions' => 0,
        'total_sales' => 0,
        'unique_customers' => 0,
        'average_transaction' => 0
    ];
}

// Get the transaction data
$monthlyTransactions = getMonthlyTransactions($conn, $currentYear, $currentMonth);
$categoryTransactions = getTransactionsByCategory($conn, $currentYear, $currentMonth);
$topProducts = getTopSellingProducts($conn, $currentYear, $currentMonth);
$monthlySummary = getMonthlySummary($conn, $currentYear, $currentMonth);

// Format data for charts
$dates = [];
$transactionCounts = [];
$salesData = [];

// Get the number of days in the current month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

// Initialize data for all days of the month
for ($day = 1; $day <= $daysInMonth; $day++) {
    $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
    $dates[] = $day; // Just the day number for the x-axis
    
    if (isset($monthlyTransactions[$date])) {
        $transactionCounts[] = $monthlyTransactions[$date]['total_transactions'];
        $salesData[] = $monthlyTransactions[$date]['total_sales'];
    } else {
        $transactionCounts[] = 0;
        $salesData[] = 0;
    }
}

// Format category data
$categories = array_keys($categoryTransactions);
$categoryCounts = array_column($categoryTransactions, 'total_transactions');
$categorySales = array_column($categoryTransactions, 'total_sales');

// Format top products data
$productNames = array_keys($topProducts);
$productSales = array_column($topProducts, 'total_sales');
$productQuantities = array_column($topProducts, 'total_quantity');

// Convert to JSON for JavaScript
$dailyDataJSON = json_encode([
    'dates' => $dates,
    'transactions' => $transactionCounts,
    'sales' => $salesData
]);

$categoryDataJSON = json_encode([
    'categories' => $categories,
    'transactions' => $categoryCounts,
    'sales' => $categorySales
]);

$productDataJSON = json_encode([
    'names' => $productNames,
    'sales' => $productSales,
    'quantities' => $productQuantities
]);

// Get month name
$monthName = date('F', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

// Helper function to generate month selection dropdown
function generateMonthDropdown($selectedMonth) {
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    
    $html = '<select name="month" id="month-select" class="form-select">';
    foreach ($months as $num => $name) {
        $selected = ($num == $selectedMonth) ? 'selected' : '';
        $html .= "<option value=\"$num\" $selected>$name</option>";
    }
    $html .= '</select>';
    
    return $html;
}

// Helper function to generate year selection dropdown
function generateYearDropdown($selectedYear) {
    $currentYear = date('Y');
    $startYear = $currentYear - 5; // Show 5 years in the past
    
    $html = '<select name="year" id="year-select" class="form-select">';
    for ($year = $currentYear; $year >= $startYear; $year--) {
        $selected = ($year == $selectedYear) ? 'selected' : '';
        $html .= "<option value=\"$year\" $selected>$year</option>";
    }
    $html .= '</select>';
    
    return $html;
}
?>