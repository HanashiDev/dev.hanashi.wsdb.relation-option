{if $referencedRecords|isset}
	{foreach from=$referencedRecords item=referencedDatabase}
		<section class="box">
			<h2 class="boxTitle">
				{lang}dev.hanashi.wsdb.relation.connected{/lang}
				{$referencedDatabase['database']}
			</h2>

			<div class="boxContent">
				<ol class="sidebarList">
					{foreach from=$referencedDatabase['records'] item=referencedRecord}
						<li class="sidebarListItem">
							{if $referencedDatabase['enableCoverPhoto']}
								<div class="sidebarListItem__image">
									<a href="{$referencedRecord->getLink()}">
										<img
											src="{$referencedRecord->getCoverPhoto()->getUrl('small')}"
											style="max-width: 70px; max-height: 50px; object-fit: cover; object-position: center center;"
											height="{$referencedRecord->getCoverPhoto()->getHeight('small')}"
											width="{$referencedRecord->getCoverPhoto()->getWidth('small')}"
											loading="lazy"
											alt=""
										>
									</a>
								</div>
							{/if}
							<div class="sidebarListItem__content" style="justify-content: center">
								<h3 class="sidebarListItem__title">
									<a href="{$referencedRecord->getLink()}" class="sidebarListItem__link">{$referencedRecord->getTitle()}</a>
								</h3>
							</div>
						</li>
					{/foreach}
				</ol>
			</div>
		</section>
	{/foreach}
{/if}
