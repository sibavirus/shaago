<?php
require_once 'includes/header.php';
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<script>
	$(function() {
		$("#startDate").datepicker({
			format: "<?php echo $dateformat; ?>",
			autoclose: true
		});

		$("#endDate").datepicker({
			format: "<?php echo $dateformat; ?>",
			autoclose: true
		});
	});
</script>

<?php
$url_start 		= "";
$url_end 		= "";
$url_outlet = '';
$url_category = '';
$url_paid_by = '';
if (isset($_GET["report"])) {
	$url_outlet = $_GET['outlet'];
    $url_category = $_GET['category'];
    $url_paid_by = $_GET['paid'];
    $url_start = $_GET['start_date'];
    $url_end = $_GET['end_date'];
}
?>

<script type="text/javascript" src="<?= base_url() ?>assets/js/datatables/jquery-1.12.3.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/datatables/jquery.dataTables.min.js"></script>
<link href="<?= base_url() ?>assets/js/datatables/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable();
	});
</script>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $this->lang->line("sold_by_product_report_title"); ?></h1>
		</div>
	</div>
	<!--/.row-->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form action="<?= base_url() ?>reports/sold_by_products" method="get">
						<div class="row">
						<div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $lang_outlets; ?></label>
                                    <select name="outlet" class="form-control" required>
                                        <?php
                                        if ($user_role == '1') {
                                        ?>
                                            <option value=""><?php echo $lang_choose_outlet; ?></option>
                                            <option value="-" <?php if ($url_outlet == '-') {
                                                                    echo 'selected="selected"';
                                                                } ?>><?php echo $lang_all_outlets; ?></option>
                                        <?php

                                        }
                                        ?>

                                        <?php
                                        if ($user_role == '1') {
                                            $outletData = $this->Constant_model->getDataAll('outlets', 'id', 'ASC');
                                        } else {
                                            $outletData = $this->Constant_model->getDataOneColumn('outlets', 'id', "$user_outlet");
                                        }
                                        for ($o = 0; $o < count($outletData); ++$o) {
                                            $outlet_id = $outletData[$o]->id;
                                            $outlet_fn = $outletData[$o]->name; ?>
                                            <option value="<?php echo $outlet_id; ?>" <?php if ($url_outlet == $outlet_id) {
                                                                                            echo 'selected="selected"';
                                                                                        } ?>>
                                                <?php echo $outlet_fn; ?>
                                            </option>
                                        <?php

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_category; ?></label>
									<select name="category" class="form-control">
										<option value="-">All</option>
									<?php
                                        $catData = $this->Constant_model->getDataOneColumn('category', 'status', '1');
                                        for ($ct = 0; $ct < count($catData); ++$ct) {
                                            $catDta_id = $catData[$ct]->id;
                                            $catDta_name = $catData[$ct]->name; ?>
											<option value="<?php echo $catDta_id; ?>" <?php if ($url_category == $catDta_id) { echo 'selected="selected"'; } ?>><?php echo $catDta_name; ?></option>
									<?php
                                            unset($catDta_id);
                                            unset($catDta_name);
                                        }
                                    ?>
									</select>
								</div>
							</div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $lang_paid_by; ?></label>
                                    <select name="paid" class="form-control" required>
                                        <option value=""><?php echo $lang_choose_paid_by; ?></option>
                                        <option value="-" <?php if ($url_paid_by == '-') {
                                                                echo 'selected="selected"';
                                                            } ?>><?php echo $lang_all; ?></option>
                                        <?php
                                        $paymentData = $this->Constant_model->getDataAll('payment_method', 'name', 'ASC');
                                        for ($p = 0; $p < count($paymentData); ++$p) {
                                            $pay_id = $paymentData[$p]->id;
                                            $pay_name = $paymentData[$p]->name; ?>
                                            <option value="<?php echo $pay_id; ?>" <?php if ($url_paid_by == "$pay_id") {
                                                                                        echo 'selected="selected"';
                                                                                    } ?>>
                                                <?php echo $pay_name; ?>
                                            </option>
                                        <?php

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-4">
								<div class="form-group">
									<label><?php echo $lang_start_date; ?></label>
									<input type="text" name="start_date" class="form-control" id="startDate" required autocomplete="off" value="<?php echo $url_start; ?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label><?php echo $lang_end_date; ?></label>
									<input type="text" name="end_date" class="form-control" id="endDate" required autocomplete="off" value="<?php echo $url_end; ?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>&nbsp;</label><br />
									<input type="hidden" name="report" value="1" />
									<input type="submit" class="btn btn-primary" value="<?php echo $lang_get_report; ?>" />
								</div>
							</div>
						</div>
					</form>

					<?php
					if (isset($_GET["report"])) {
						if ($site_dateformat == 'd/m/Y') {
							$startArray 	= explode('/', $url_start);
							$endArray 		= explode('/', $url_end);

							$start_day 		= $startArray[0];
							$start_mon 		= $startArray[1];
							$start_yea 		= $startArray[2];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[0];
							$end_mon 		= $endArray[1];
							$end_yea 		= $endArray[2];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}
						if ($site_dateformat == 'd.m.Y') {
							$startArray 	= explode('.', $url_start);
							$endArray 		= explode('.', $url_end);

							$start_day 		= $startArray[0];
							$start_mon 		= $startArray[1];
							$start_yea 		= $startArray[2];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[0];
							$end_mon 		= $endArray[1];
							$end_yea 		= $endArray[2];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}
						if ($site_dateformat == 'd-m-Y') {
							$startArray 	= explode('-', $url_start);
							$endArray 		= explode('-', $url_end);

							$start_day 		= $startArray[0];
							$start_mon 		= $startArray[1];
							$start_yea 		= $startArray[2];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[0];
							$end_mon 		= $endArray[1];
							$end_yea 		= $endArray[2];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}

						if ($site_dateformat == 'm/d/Y') {
							$startArray 	= explode('/', $url_start);
							$endArray 		= explode('/', $url_end);

							$start_day 		= $startArray[1];
							$start_mon 		= $startArray[0];
							$start_yea 		= $startArray[2];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[1];
							$end_mon 		= $endArray[0];
							$end_yea 		= $endArray[2];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}
						if ($site_dateformat == 'm.d.Y') {
							$startArray 	= explode('.', $url_start);
							$endArray	 	= explode('.', $url_end);

							$start_day 		= $startArray[1];
							$start_mon 		= $startArray[0];
							$start_yea 		= $startArray[2];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[1];
							$end_mon 		= $endArray[0];
							$end_yea 		= $endArray[2];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}
						if ($site_dateformat == 'm-d-Y') {
							$startArray 	= explode('-', $url_start);
							$endArray 		= explode('-', $url_end);

							$start_day 		= $startArray[1];
							$start_mon 		= $startArray[0];
							$start_yea 		= $startArray[2];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[1];
							$end_mon 		= $endArray[0];
							$end_yea 		= $endArray[2];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}

						if ($site_dateformat == 'Y.m.d') {
							$startArray 	= explode('.', $url_start);
							$endArray 		= explode('.', $url_end);

							$start_day 		= $startArray[2];
							$start_mon 		= $startArray[1];
							$start_yea 		= $startArray[0];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[2];
							$end_mon 		= $endArray[1];
							$end_yea 		= $endArray[0];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}
						if ($site_dateformat == 'Y/m/d') {
							$startArray 	= explode('/', $url_start);
							$endArray 		= explode('/', $url_end);

							$start_day 		= $startArray[2];
							$start_mon 		= $startArray[1];
							$start_yea 		= $startArray[0];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[2];
							$end_mon 		= $endArray[1];
							$end_yea 		= $endArray[0];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}
						if ($site_dateformat == 'Y-m-d') {
							$startArray 	= explode('-', $url_start);
							$endArray 		= explode('-', $url_end);

							$start_day	 	= $startArray[2];
							$start_mon 		= $startArray[1];
							$start_yea 		= $startArray[0];

							$url_start 		= $start_yea . '-' . $start_mon . '-' . $start_day;

							$end_day 		= $endArray[2];
							$end_mon 		= $endArray[1];
							$end_yea 		= $endArray[0];

							$url_end 		= $end_yea . '-' . $end_mon . '-' . $end_day;
						}

					?>
						<div class="row" style="margin-top: 10px;">
							<div class="col-md-12" style="text-align: right;">
								<a href="<?= base_url() ?>reports/exportSoldByModelReport?url_start=<?php echo $url_start; ?>&url_end=<?php echo $url_end; ?>" target="_blank">
									<button type="button" class="btn btn-success" style="background-color: #5cb85c; border-color: #4cae4c;"><?php echo $lang_export_to_excel; ?></button>
								</a>
							</div>
						</div>
						<div class="row" style="margin-top: 10px;">
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="example" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th width="28%"><?php echo $this->lang->line("product_code"); ?></th>
												<th width="28%"><?php echo $this->lang->line("product_name"); ?></th>
												<th width="28%"><?php echo $this->lang->line("product_category"); ?></th>
												<th width="15%"><?php echo $this->lang->line("total_sold"); ?></th>
												<th width="15%"><?php echo $this->lang->line("sold_by_product_sold_qty"); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$start_dtm 		= $url_start . " 00:00:00";
											$end_dtm 		= $url_end . " 23:59:59";
											$overall_amount = 0;
											$overall_qty = 0;
											$url_outlet = $_GET['outlet'];
											$url_category = $_GET['category'];
											$url_paid_by = $_GET['paid'];

											$conditions = "";
											if($url_outlet !='' && $url_outlet !='-')
												$conditions .= " AND orders.outlet_id=$url_outlet";
											if($url_category !='' && $url_category !='-')
												$conditions .= " AND order_items.product_category=$url_category";
											if($url_paid_by !='' && $url_paid_by !='-')
												$conditions .= " AND orders.payment_method=$url_paid_by";

											$ordItemResult 	= $this->db->query(
											"SELECT DISTINCT product_code, IF(products.name IS NOT NULL,products.name, CONCAT(max(order_items.product_name),' - (deleted product)') ) as product_name, category.name as category_name, SUM(order_items.price) as price, SUM(order_items.qty) as qty 
											FROM order_items 
											LEFT JOIN orders ON orders.id = order_items.order_id
											LEFT JOIN products ON products.code = order_items.product_code
											LEFT JOIN category ON category.id = products.category
											WHERE order_items.created_datetime >= '$start_dtm' AND order_items.created_datetime <= '$end_dtm'
											$conditions AND order_items.status = '1'
											GROUP BY product_code, products.name, category.name
											ORDER BY product_name ASC");

											$ordItemData 	= $ordItemResult->result_array();
											unset($ordItemResult);
											?>

											<?php
											if (count($ordItemData) > 0) {
												foreach ($ordItemData as $row) { 
													$overall_amount+=$row['price'];
													$overall_qty+=$row['qty'];
											?>
													<tr>
														<td><?php echo $row['product_code']; ?></td>
														<td><?php echo $row['product_name']; ?></td>
														<td><?php echo $row['category_name']; ?></td>
														<td><?php echo $row['price']; ?></td>
														<td><?php echo $row['qty']; ?></td>
													</tr>
											<?php
												}
											}

											unset($ordItemData);
											?>
											<tr>
												<td colspan="3"><h4><?php echo $this->lang->line("total"); ?></h4></td>
												<td><h5><?php echo number_format((float)$overall_amount, 2, '.', '');; ?></h5></td>
												<td><h5><?php echo $overall_qty; ?></h5></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					<?php
					}
					?>



				</div><!-- Panel Body // END -->
			</div><!-- Panel Default // END -->
		</div><!-- Col md 12 // END -->
	</div><!-- Row // END -->

	<br /><br /><br />

</div><!-- Right Colmn // END -->



<?php
require_once 'includes/footer.php';
?>