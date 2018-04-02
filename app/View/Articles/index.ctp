<h2>Travel Article Database</h2>
<?php if (empty($results)): ?>
	<h3>Enter your search criteria</h3>
<?php endif; ?>

<?php if ($isAdmin): ?>
	<div class="page-actions">
		<?php echo $this->Html->link("Admin Actions", '/magazines/index'); ?>
	</div>
<?php endif; ?>

<div id="search-articles-left" class="page-element-parent">
	<?php echo $this->Form->create('Article') ?>

	<?php echo $this->Form->input('magazine_id', array(
		'options' => array_combine(Set::extract('/Magazine/id', $mags), Set::extract('/Magazine/name', $mags)),
		'empty' => '(Title)'
	)) ?>

	<div class="form-entry-set">
		<?php echo $this->Form->input('published', array(
			'type' => 'date',
			'dateFormat' => 'M',
			'empty' => '(Month)')); ?>

		<?php echo $this->Form->input('published', array(
			'type' => 'date',
			'dateFormat' => 'Y',
			'minYear' => $minYear,
			'maxYear' => $maxYear,
			'label' => '&nbsp;',
			'empty' => '(Year)')); ?>
	</div>

	<div class="float-submit-buttons">
		<?php echo $this->Form->submit("Search"); ?>
		<div class="submit">
			<button id="see-all-button" class="button submit-button">Clear Search</button>
		</div>
	</div>
	<?php echo $this->Form->end() ?>
</div>

<?php if (!empty($tags) && count($results) > 1): ?>
	<div id="search-articles-right" class="page-element-parent">
		<div class="section-header-wrapper">
			<div class="section-header">
				<h4>Limit results further by tag:</h4>
				<?php echo $this->Form->input('Tag.with', array(
					'type' => 'radio',
					'legend' => false,
					'default' => 0,
					'options' => array('article has tag', 'article does not have tag'))); ?>
			</div>
		<span id="adv-titles-toggler"
		      class="page-element-toggler hidden-content-toggler rounded-field">+</span><br>
		</div>
		<div class="scroll-pane vertical-scroll-pane hidden-content-defer-hide">
			<div class="section-body ">
				<div class="tags sidebar-adv-tags small-tags">
					<?php foreach ($tags as $tag): ?>
						<?php $name = $tag['name']; ?>
						<?php $id = $tag['id']; ?>
						<?php $count = $tag['count']; ?>
						<?php //if (isset($prevTagQuery)): ?>
						<?php //if (!in_array($id, array_keys($prevTagQuery))): ?>
						<?php //$paramSuffix = implode(',', array_keys($prevTagQuery)); ?>
						<?php //echo $this->Html->link("$name ($count)", '/articles?tags=' . "$paramSuffix," . $id) ?>
						<?php //endif; ?>
						<?php //else: ?>
						<?php echo $this->Html->link("$name ($count)", '/articles?tags=' . $id) ?>
						<?php //endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>


<div class="search-query-parent">
	<div class="page-element-parent search-query">

		<?php echo $this->Paginator->counter(array(
			'format' => __('Showing {:count} article(s)')
		));
		?>

		<?php if (isset($prevTagQuery) || isset($prevSearchQuery)): ?>
			<?php if (!empty($prevSearchQuery['magazine'])): ?>
				in <span class="magazine-name"><strong><?php echo $prevSearchQuery['magazine'] ?>
					</strong> </span>
			<?php endif; ?>
			<?php if (!empty($prevSearchQuery['published'])): ?>
				published in
				<?php if (!empty($prevSearchQuery['published']['month'])): ?>
					<strong><?php echo $prevSearchQuery['published']['month'] . ' ' ?> </strong>
				<?php endif; ?>
				<?php if (!empty($prevSearchQuery['published']['year'])): ?>
					<strong><?php echo $prevSearchQuery['published']['year'] . ' ' ?> </strong>
				<?php endif; ?>
			<?php endif; ?>
			<?php if (isset($prevTagQuery['with'])): ?>
				with
				<div class="tags inline-tags">
					<?php
					foreach ($prevTagQuery['with'] as $id => $name) {
						echo $this->Html->link($name, "/articles?tags=$id&newSearch=1");
					}
					?>
				</div>
				<?php if (isset($prevTagQuery['without'])) {
					echo 'and';
				} ?>
			<?php endif; ?>
			<?php if (isset($prevTagQuery['without'])): ?>
				without
				<div class="tags inline-tags">
					<?php
					foreach ($prevTagQuery['without'] as $id => $name) {
						echo $this->Html->link($name, "/articles?tags=$id&newSearch=1");
					}
					?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>


<?php if (isset($results)): ?>
	<?php if (!empty($results)): ?>
		<table class="search-results">
			<tr>
				<th style="width: 40%"><?php echo $this->Paginator->sort('Article.name', 'Name'); ?>
				</th>
				<th style="width: 25%"><?php echo $this->Paginator->sort('Magazine.name', 'Magazine'); ?>
				</th>
				<th style="width: 25%"><?php echo $this->Paginator->sort('Article.published', 'Published'); ?>
				</th>
				<th style="width: 5%"><?php echo $this->Paginator->sort('Article.visits', 'Visits'); ?>
				</th>
				<th style="width: 5%"><?php echo $this->Paginator->sort('Article.likes', 'Likes'); ?>
				</th>
				<?php if ($isAdmin): ?>
					<th style="width: 5%"><?php echo $this->Paginator->sort('Article.is_tagged', 'Tagged?'); ?>
					</th>
				<?php endif; ?>
			</tr>
			<?php foreach ($results as $i => $r): ?>
				<tr <?php if ($isAdmin && !$r['Article']['is_tagged']): ?>
					class="untagged-article" <?php endif; ?>>
					<td><?php echo $this->Html->link($r['Article']['name'], '/articles/view/' . $r['Article']['id'], array('class' => 'article-name')); ?>
					</td>
					<td class="magazine-name"><?php echo $r['Magazine']['name'] ?>
					</td>
					<td><?php echo $r['Article']['published_display'] ?>
					</td>
					<td><?php echo $r['Article']['visits'] ?>
					</td>
					<td><span class="likes-display rounded-field"><?php echo $r['Article']['likes'] ?>
		</span>
					</td>
					<?php if ($isAdmin): ?>
						<td><?php echo $r['Article']['is_tagged'] ? 'Y' : 'N' ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		<label class="search-results empty-list-note"><strong>No results.</strong>
		</label>
	<?php endif; ?>
	<div class="paging-parent">
		<?php echo $this->Element('paging', array('model' => 'Article')) ?>
	</div>
<?php endif; ?>

<script>

	$(document).ready(function () {
		//TODO: stupid hack becuase submit button name wasn't getting passed back.
		$('#see-all-button').click(function (e) {
			e.preventDefault();
			location.href = APPROOT + 'articles/index?newSearch=1';
		});

		$('.tags a').click(function (e) {
			e.preventDefault();
			var isWithTag = $('#TagWith0').is(':checked') ? 1 : 0;
			location.href = $(this).attr('href') + '&withTag=' + isWithTag;
		});
	});
</script>
