<div class="product-detail__wrap room-detail__wrap">
	<!-- 상품 이미지 슬라이드 -->
	<div class="product-detail__img">
		<div id="roomModalSlider" class="splide splide__product">
			<div class="splide__track">
				<ul class="splide__list">
					<?php $roomImages = explode(':-:', $room->image_paths); ?>
					<?php if (!empty($roomImages[0])) : ?>
						<?php foreach ($roomImages as $roomImage) : ?>
							<li class="splide__slide splide__list__product"><img src="<?= $roomImage; ?>" alt=""></li>
						<?php endforeach; ?>
					<?php else : ?>
						<li class="splide__slide splide__list__product"><img src="/assets/app/images/demo/moongcle-noimg.png" alt=""></li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="slide-counter">
				<span class="current-slide">1</span> / <span class="total-slides"></span>
			</div>
		</div>
	</div>
	<!-- //상품 이미지 슬라이드 -->
	<!-- 상품 타이틀 -->
	<div class="product-detail__tit">
		<div class="product-tit">
			<p class="product-name"><?= $room->room_name; ?></p>
		</div>
		<ul class="room-option">
			<?php if (empty($room->views)) : ?>
				<!-- <li class="option-view">
					<div>전망 없음</div>
				</li> -->
			<?php else : ?>
				<?php $roomViews = explode(':-:', $room->views); ?>
				<li class="option-view">
					<div>
						<?php foreach ($roomViews as $roomView) : ?>
							<div><?= $roomView; ?></div>
						<?php endforeach; ?>
					</div>
				</li>
			<?php endif; ?>
			<?php $bedTypes = json_decode($room->room_bed_type, true); ?>
			
            <?php
            $totalBeds = array_sum($bedTypes);
            ?>

            <?php if ($totalBeds > 0) : ?>
                <li class="option-bed">
                    <div>
                        <?php
                        $bedNames = [
                            'dormitory_beds' => '도미토리',
                            'single_beds' => '싱글베드',
                            'super_single_beds' => '슈퍼싱글베드',
                            'semi_double_beds' => '세미더블베드',
                            'double_beds' => '더블베드',
                            'queen_beds' => '퀸베드',
                            'king_beds' => '킹베드',
                            'hollywood_twin_beds' => '할리우드베드',
                            'double_story_beds' => '이층 침대',
                            'bunk_beds' => '벙크베드',
                            'rollaway_beds' => '간이 침대',
                            'futon_beds' => '요이불 세트',
                            'capsule_beds' => '캡슐 침대',
                            'sofa_beds' => '소파베드',
                            'air_beds' => '에어베드'
                        ];

                        foreach ($bedTypes as $bedType => $bedCount) {
                            if ($bedCount == 0) continue;

                            if (isset($bedNames[$bedType])) {
                                echo '<div>' . $bedNames[$bedType] . ' ' . $bedCount . '개</div>';
                            }
                        }
                        ?>
                    </div>
                </li>
            <?php endif; ?>

            <?php if (!empty($room->room_size)) : ?>
				<li class="option-area">객실크기 <?= $room->room_size; ?>&#13217;</li>
			<?php endif; ?>
			<li class="option-people">기준 <?= $room->room_standard_person; ?>명 / 최대 <?= $room->room_max_person; ?>명</li>
		</ul>
		<!-- 이용인원 -->
		<div class="product-detail__people">
			<p class="title">이용인원</p>
			<ul>
				<li>
					<p class="tit">기준인원</p>
					<p class="desc"><?= $room->room_standard_person; ?>명</p>
				</li>
				<li>
					<p class="tit">최대인원</p>
					<p class="desc"><?= $room->room_max_person; ?>명</p>
				</li>
				<?php if ($partner->partner_thirdparty != 'onda') : ?>
					<li>
						<p class="tit">추가인원요금<br>(현장결제)</p>
						<p class="desc">
							성인 1명 (<?= number_format($room->room_adult_additional_price); ?>원) <br>
							아동 1명 (<?= number_format($room->room_child_additional_price); ?>원) <br>
							유아 1명 (<?= number_format($room->room_tiny_additional_price); ?>원)
						</p>
					</li>
				<?php endif; ?>
			</ul>

			<?php if ($partner->partner_thirdparty == 'onda') : ?>
				<p class="txt-primary">* 기준 인원을 초과하는 경우 추가요금에 대한 현장결제가 필요할 수도 있습니다.</p>
			<?php else : ?>
				<p class="txt-primary">* 객실 요금은 기준 인원에 대한 요금이며, 최대 인원까지 투숙은 가능하나 현장에서 추가인원요금이 발생할 수 있습니다.</p>
			<?php endif; ?>
		</div>
		<!-- //이용인원 -->
	</div>
	<!-- //상품 타이틀 -->
	<!-- 숙소 공지사항 및 정보 -->
	<div class="product-detail__notice">
		<?php if (!empty($room->room_other_notes)) : ?>
			<div class="bullet__wrap">
				<p class="title">객실정보</p>
				<div class="detail-info"><?= $room->room_other_notes; ?></div>
			</div>
		<?php endif; ?>
		<?php if (!empty($roomTagList['roomtype'])) : ?>
			<div class="bullet__wrap">
				<p class="title">객실타입</p>
				<!-- <a href="" class="btn-txt__arrow">더보기</a> -->
				<div class="amenities__list" style="margin-left: 4rem;">
					<ul style="list-style: disc;">
						<?php foreach ($roomTagList['roomtype'] as $tag) : ?>
							<li style="display: list-item;">
								<!-- <img src="/uploads/tags/<?= $tag->tag_machine_name; ?>.png" alt=""> -->
								<span><?= $tag->tag_name; ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
		<?php if (!empty($roomTagList['room_amenity'])) : ?>
			<div class="bullet__wrap">
				<p class="title">편의시설</p>
				<!-- <a href="" class="btn-txt__arrow">더보기</a> -->
				<div class="amenities__list" style="margin-left: 4rem;">
					<ul style="list-style: disc;">
						<?php foreach ($roomTagList['room_amenity'] as $tag) : ?>
							<li style="display: list-item;">
								<!-- <img src="/uploads/tags/<?= $tag->tag_machine_name; ?>.png" alt=""> -->
								<span><?= $tag->tag_name; ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
		<?php if (!empty($roomTagList['barrierfree_room'])) : ?>
			<div class="bullet__wrap">
				<p class="title">공용공간 베리어프리 시설 및 서비스</p>
				<div class="amenities__list" style="margin-left: 2rem;">
					<ul>
						<?php foreach ($roomTagList['barrierfree_room'] as $tag) : ?>
							<li>
								<!-- <img src="/uploads/tags/<?= $tag->tag_machine_name; ?>.png" alt=""> -->
								<span><?= $tag->tag_name; ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<!-- //숙소 공지사항 및 정보 -->

</div>