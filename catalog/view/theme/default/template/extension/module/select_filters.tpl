<?php foreach ($filters as $filter): ?>
<option value="<?php echo $filter['keyword'] ?>" class="custom_filter" data-filter_id="<?php echo $filter['filter_id'] ?>" id="<?php echo $filter['filter_id'] ?>"><?php echo $filter['name'] ?></option>
<?php endforeach; ?>