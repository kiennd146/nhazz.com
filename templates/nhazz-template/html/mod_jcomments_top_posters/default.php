<?php
// no direct access
defined('_JEXEC') or die;
?>
<script>
	(function($){
		$(document).ready(function() {
			$( "#topcommenter_tabs" ).tabs();
			
			<?php for ($i=0; $i<count($list); $i++) : ?>
			$( "#topcommenter_tabs-<?php echo $i?>" ).tabs();
			<?php endfor; ?>
			
			//$(".tab-content-group").equalHeights();
		});
	})(jQuery);
</script>

<div id="topcommenter">
	<h2>Top commenters</h2>
	<div id="topcommenter_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<div style="clear:both"></div>
<?php //<div style="clear:both"></div>
 if (!empty($list)) :?>
		
		<?php $i = 0; ?>		
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
		<?php $first = true; ?>
		<?php foreach ($list as $interval=>$items) : ?>
			<li class="ui-state-default ui-corner-top <?php if($first == true): ?> ui-tabs-active ui-state-active <?php endif ?>" role="tab" tabindex="0" aria-controls="topcommenter_tabs-<?php echo $i?>" aria-labelledby="ui-id-<?php echo $i?>" aria-selected="true">
                <a href="#topcommenter_tabs-<?php echo $i?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-<?php echo $i?>"><?php echo $lang_interval[$interval]?></a>
             </li>
		<?php $first = false; ?>
		<?php $i++; ?>
		<?php endforeach; ?>
		</ul>
		
		<?php $i = 0; ?>
		
		<?php $first = true; ?>
		<?php foreach ($list as $interval=>$items) : ?>
		<?php $count = 1; ?>	
		<div id="topcommenter_tabs-<?php echo $i;?>" aria-labelledby="ui-id-<?php echo $i;?>" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs ui-widget ui-corner-all" role="tabpanel" aria-expanded="<?php if($first == true): ?>true<?php else: ?>false<?php endif ?>" aria-hidden="<?php if($first == false): ?>true<?php else: ?>false<?php endif ?>">
			<?php $j = $tmp = 0; ?>
            <?php $nbperpage = 5; ?>
            
            <?php foreach ($items as $item) : ?>
            <?php $n = floor($j / $nbperpage); ?>
            <?php $m = ceil($j / $nbperpage); ?>
			<?php //echo 'n:',$n,'-m:',$m,'<br>' ?>
            <?php if($n == $m): ?>
			
			<?php $countdown = $nbperpage; ?>
			<div id="topcommenter_tabs-<?php echo $i?>-<?php echo $n?>" aria-labelledby="ui-id-aa<?php echo ($j+$countdown)?>" class="tab-content-group ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="<?php if($countdown == $nbperpage): ?>true<?php else: ?>false<?php endif ?>" aria-hidden="<?php if($countdown != $nbperpage): ?>true<?php else: ?>false<?php endif ?>">
			
            <?php endif; ?>
			
				<div class="tab-content" style="height: 25px; overflow: auto;">
					<div class="tab-item tab-number"><?php echo $count?></div>
					<div class="tab-item tab-avatar"><a href="<?php echo $item->profileLink; ?>"><?php echo $item->avatar; ?></a></div>
					<div class="tab-item tab-name"><a href="<?php echo $item->profileLink; ?>"><?php echo $item->displayAuthorName; ?></a></div>
					<div class="tab-item tab-state-origin"></div>
				</div>
			<?php $countdown--; ?>
            
            <?php if($countdown == 0 || $j==count($items)-1): ?>
			</div>
            <?php endif; ?>
            
            <?php $j++; ?>
			<?php $count++; ?>
            
            <?php endforeach; ?>
            
			<ul class="tab-paging ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
			<?php $first_page = true;?>
			
			<?php for($page=1; $page<=$n+1; $page++): ?>
				<li class="ui-state-default ui-corner-top <?php if($first_page == true): ?> ui-tabs-active ui-state-active <?php endif ?>" role="tab" tabindex="0" aria-controls="topcommenter_tabs-<?php echo $i?>-<?php echo $p?>" aria-labelledby="ui-id-<?php echo ($page+1000) ?>" aria-selected="true">
					<a href="#topcommenter_tabs-<?php echo $i?>-<?php echo $page-1?>" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-<?php echo ($page+1000) ?>">
						<?php echo $page?>
					</a>
				</li>
				<?php $first_page = false;?>
			<?php endfor ?>
			
			</ul>
		</div>	
			
		<?php $first = false; ?>
		<?php $i++; ?>
		<?php endforeach; ?>
		
	</div>
</div>

<?php endif; ?>

<?php /*
<div style="clear:both"></div>
<div id="topcommenter_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="topcommenter_tabs-1" aria-labelledby="ui-id-1" aria-selected="true"><a href="#topcommenter_tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Today</a></li>
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="topcommenter_tabs-2" aria-labelledby="ui-id-2" aria-selected="false"><a href="#topcommenter_tabs-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">This Week</a></li>
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="topcommenter_tabs-3" aria-labelledby="ui-id-3" aria-selected="false"><a href="#topcommenter_tabs-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">This Month</a></li>
	</ul>
	<div id="topcommenter_tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs ui-widget ui-corner-all" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">
		
		<div id="topcommenter_tabs-1-1" aria-labelledby="ui-id-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false">
			<div class="tab-content" style="height: 25px; overflow: auto;">
				<div class="tab-item tab-number">1</div>
				<div class="tab-item tab-avatar"><a href="#"><img src="./index.php_files/avatar_small.jpg"></a></div>
				<div class="tab-item tab-name"><a href="#">decoenthusiaste</a></div>
				<div class="tab-item tab-state-origin"></div>
			</div>
			<div class="tab-content" style="height: 25px; overflow: auto;">
				<div class="tab-item tab-number">2</div>
				<div class="tab-item tab-avatar"><a href="#"><img src="./index.php_files/avatar_small.jpg"></a></div>
				<div class="tab-item tab-name"><a href="#">decoenthusiaste</a></div>
				<div class="tab-item tab-state-origin"></div>
			</div>
		</div>
		<div id="topcommenter_tabs-1-2" aria-labelledby="ui-id-5" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true">
			<div class="tab-content" style="height: 25px; overflow: auto;">
				<div class="tab-item tab-number">3</div>
				<div class="tab-item tab-avatar"><a href="#"><img src="./index.php_files/avatar_small.jpg"></a></div>
				<div class="tab-item tab-name"><a href="#">decoenthusiaste</a></div>
				<div class="tab-item tab-state-origin"></div>
			</div>
			<div class="tab-content" style="height: 25px; overflow: auto;">
				<div class="tab-item tab-number">4</div>
				<div class="tab-item tab-avatar"><a href="#"><img src="./index.php_files/avatar_small.jpg"></a></div>
				<div class="tab-item tab-name"><a href="#">decoenthusiaste</a></div>
				<div class="tab-item tab-state-origin"></div>
			</div>
		</div>
		<ul class="tab-paging ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="topcommenter_tabs-1-1" aria-labelledby="ui-id-4" aria-selected="true"><a href="#topcommenter_tabs-1-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4">1</a></li>
			<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="topcommenter_tabs-1-2" aria-labelledby="ui-id-5" aria-selected="false"><a href="#topcommenter_tabs-1-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-5">2</a></li>
		</ul>
	</div>
	<div id="topcommenter_tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true">
		<div id="topcommenter_tabs-2-1">
			<p class="tab-content" style="height: 25px; overflow: auto;">Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
			
		</div>
		<div id="topcommenter_tabs-2-2">
			<p class="tab-content" style="height: 25px; overflow: auto;">Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
			
		</div>
	</div>
	<div id="topcommenter_tabs-3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true">
		<div id="topcommenter_tabs-3-1">
			<p class="tab-content" style="height: 25px; overflow: auto;">Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
			
		</div>
		<div id="topcommenter_tabs-3-2">
			<p class="tab-content" style="height: 25px; overflow: auto;">Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
			
		</div>
	</div>
</div>
*/
?> 