<div class="snippet-5sterrenspecialist">
    <div class="rating-box">
        <div class="rating" style="width:<?php echo round($rating * 10);?>%"></div>
    </div>
    <div class="schema-5sterrenspecialist">
        <div itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
       <span itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization" style="display: none;">
             <span itemprop="name"><?php echo $data['companyName']; ?></span>
          </span>
            <meta itemprop="bestRating" content="10">
            <p>
                Beoordeling <span itemprop="ratingValue"><?php echo $rating;?></span>
                gebaseerd op <span itemprop="ratingCount"><?php echo $data['ratingCount'];?></span>
                individuele klant<?php echo $plural;?> op
                <a href="<?php echo $data['url'];?>" target="_blank">5-sterrenspecialist</a>
            </p>
        </div>
    </div>
</div>