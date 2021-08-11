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
$search_name = "";
$search_code = "";
$search_category = '';
if (isset($_GET["report"])) {
    $search_category = $_GET['category'];
    $search_code = $_GET['code'];
    $search_name = $_GET['name'];
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
			<h1 class="page-header"><?php echo $this->lang->line("product_profit_margin"); ?></h1>
		</div>
	</div>
	<!--/.row-->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form action="<?= base_url() ?>reports/product_margin" method="get">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_code; ?></label>
									<input type="text" name="code" class="form-control" value="<?php echo $search_code; ?>" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_name; ?></label>
									<input type="text" name="name" class="form-control" value="<?php echo $search_name; ?>" />
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
											<option value="<?php echo $catDta_id; ?>" <?php if ($search_category == $catDta_id) { echo 'selected="selected"'; } ?>><?php echo $catDta_name; ?></option>
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
									<label>&nbsp;</label><br />
									<input type="hidden" name="report" value="1" />
									<input type="submit" class="btn btn-primary" value="<?php echo $lang_get_report; ?>" />
								</div>
							</div>
						</div>
					</form>

					<?php
					if (isset($_GET["report"])) {

					?>
						<div class="row" style="margin-top: 10px;">
							<div class="col-md-12" style="text-align: right;">
								<a href="<?= base_url() ?>reports/exportProductMarginReport?name=<?php echo $search_name; ?>&code=<?php echo $search_code; ?>&category=<?php echo $search_category; ?>" target="_blank">
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
												<th width="15%"><?php echo $this->lang->line("cost"); ?></th>
												<th width="15%"><?php echo $this->lang->line("price"); ?></th>
												<th width="15%"><?php echo $this->lang->line("product_margin"); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php

											$conditions = "";
											if($search_name !='' && $search_name !='-')
												$conditions .= " AND products.name LIKE '%$search_name%'";
											if($search_code !='' && $search_code !='-')
												$conditions .= " AND products.code LIKE '%$search_code%'";
											if($search_category !='' && $search_category !='-')
												$conditions .= " AND products.category=$search_category";

											$ordItemResult 	= $this->db->query(
											"SELECT *, products.name as products_name , category.name as category_name, (retail_price - purchase_price) as margin 
											FROM products 
											LEFT JOIN category ON category.id = products.category
											WHERE products.created_datetime != '0000-00-00 00:00:00'
											$conditions
											ORDER BY products.name ASC");

											$ordItemData 	= $ordItemResult->result_array();
											unset($ordItemResult);
											?>

											<?php
											if (count($ordItemData) > 0) {
												foreach ($ordItemData as $row) { 
											?>
													<tr>
														<td><?php echo $row['code']; ?></td>
														<td><?php echo $row['products_name']; ?></td>
														<td><?php echo $row['category_name']; ?></td>
														<td><?php echo $row['purchase_price']; ?></td>
														<td><?php echo $row['retail_price']; ?></td>
														<td><?php echo $row['margin']; ?></td>
													</tr>
											<?php
												}
											}

											unset($ordItemData);
											?>
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