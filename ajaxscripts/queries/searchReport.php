<?php
include('../../config.php');
include('../../includes/functions.php');

$reportCategory = mysqli_real_escape_string($mysqli, $_POST['reportCategory']);
$reportStartDate = mysqli_real_escape_string($mysqli, $_POST['reportStartDate']);
$reportEndDate = mysqli_real_escape_string($mysqli, $_POST['reportEndDate']);

if ($reportCategory == 'Income') {
    // Fetch data for the specified date range
    $getResults = $mysqli->query("
        SELECT 
            expenditureCategory,
            DATE_FORMAT(expenditureDate, '%Y-%m') as monthYear, 
            SUM(expenditureAmount) as totalAmount 
        FROM 
            expenditures 
        WHERE 
            expenditureDate BETWEEN '$reportStartDate' AND '$reportEndDate'
        GROUP BY 
            expenditureCategory, monthYear
        ORDER BY 
            monthYear ASC
    ");

    $data = [];
    while ($row = $getResults->fetch_assoc()) {
        $data[] = $row;
    }
?>
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom: 30px;">
        <div id="printArea">
            <h5 class="font-weight-bolder mb-0">Summary</h5>

            <div class="table-responsive">
                <table class="table table-flush" id="siteTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expenditure Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getResultsDetails = $mysqli->query("
                            SELECT * FROM expenditures 
                            WHERE expenditureDate BETWEEN '$reportStartDate' AND '$reportEndDate'
                        ");
                        while ($resResults = $getResultsDetails->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $resResults['expenditureName']; ?></td>
                                <td><?php echo $resResults['expenditureCategory']; ?></td>
                                <td><?php echo $resResults['expenditureDate']; ?></td>
                                <td><?php echo $resResults['expenditureDescription']; ?></td>
                                <td><?php echo $resResults['expenditureAmount']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <canvas id="myChart"></canvas>
        </div>

        <?php if (mysqli_num_rows($getResultsDetails) > 0) { ?>
            <button id="printButton" style="width: 10%;" class="btn btn-sm bg-gradient-dark mb-0 js-btn-next mt-5" type="button" title="Print Report">Print</button>
        <?php } else {
            echo "<span style='text-align:center'>No record found</span>";
        } ?>

    </div>

<?php
}
?>



<script>
    document.getElementById('printButton').addEventListener('click', function() {
        printJS({
            printable: 'printArea',
            type: 'html',
            style: `
                body { font-family: Poppins, sans-serif; text-align: center; }
                p { color: #666; }
            `
        });
    });

    const ctx = document.getElementById('myChart');

    const data = <?php echo json_encode($data); ?>;

    const categories = [...new Set(data.map(item => item.expenditureCategory))];
    const months = [...new Set(data.map(item => item.monthYear))];

    // Generate a color for each category
    const generateColors = (num) => {
        const colors = [];
        for (let i = 0; i < num; i++) {
            colors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.2)`);
        }
        return colors;
    };

    const categoryColors = generateColors(categories.length);

    const datasets = categories.map((category, index) => {
        return {
            label: category,
            data: months.map(month => {
                const item = data.find(d => d.monthYear === month && d.expenditureCategory === category);
                return item ? item.totalAmount : 0;
            }),
            backgroundColor: categoryColors[index],
            borderColor: categoryColors[index].replace('0.2', '1'),
            borderWidth: 1
        };
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        autoSkip: false,
                        maxRotation: 90,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    stacked: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const category = context.dataset.label;
                            const amount = context.raw;
                            return `${category}: ${amount.toLocaleString('en-US', { style: 'currency', currency: 'USD' })}`;
                        }
                    }
                }
            }
        }
    });
</script>