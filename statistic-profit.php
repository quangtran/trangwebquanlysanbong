<style>
    <?php 
        require_once('./CSS/statistic-profit.css');
    ?>
</style>

<?php
    ?>
        <div class="statistic-profit-container">
            <div class="statistic-profit-title">Thống kê doanh thu</div>

            <div class="profits-list">
                <table>
                    <tr>
                        <th>STT</th>
                        <th>Ngày</th>
                        <th>Doanh thu trong ngày</th>
                    </tr>

                    <?php
                        $db = getDatabase();
                        $profitsData = getProfits($db);
                        $totalProfit = 0;

                        if ($profitsData != null && $profitsData -> num_rows > 0) {
                            $number = 0;

                            while ($data = $profitsData -> fetch_assoc()) {
                                $paymentId = $data['payment_id'];

                                //Lấy dữ liệu ttoan qua ID
                                $paymentDetail = getPaymentById($paymentId);
                                $paymentData = $paymentDetail -> fetch_assoc();
                                $totalCost = $paymentData['total_cost'];
                                $paymentDate = $paymentData['payment_date'];

                                //Tổng tiền
                                $totalProfit += $totalCost;

                                ?>
                                    <tr>
                                        <td><?= $number += 1 ?></td>
                                        <td><?= $paymentDate ?></td>
                                        <td><?= number_format($totalCost) ?></td>
                                    </tr>
                                <?php
                            }
                        }
                    ?>
                </table>
            </div>

            <?php
                if ($totalProfit != 0) {
                    ?>

                        <div class="total-profit">Tổng doanh thu của sân bóng: <span><?= number_format($totalProfit) ?></span></div>
                    <?php
                }

                else {
                    ?>
                        <div class="total-profit">Tổng doanh thu của sân bóng: <span>0đ</span></div>
                    <?php
                }
            ?>
        </div>
    <?php
?>