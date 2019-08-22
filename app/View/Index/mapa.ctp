<div id="info"></div>
<div id="gmap-tabs-1" style="position: relative; overflow: hidden; min-height: 500px;"></div>
<div style="display: none;">
    <ul>
        <?php
        if (isset($geoLocalizacoes)) {
            $sizeGeo = count($geoLocalizacoes);
            for ($i = 0; $i < $sizeGeo; $i++) {
                ?>
                <li>
                    <span id="nome-<?php echo $i; ?>"><?php echo $geoLocalizacoes[$i]['p']['nome'] . ' ' . $geoLocalizacoes[$i]['p']['sobrenome']; ?></span>
                    <span id="lat-<?php echo $i; ?>"><?php echo $geoLocalizacoes[$i]['e']['latitude']; ?></span>
                    <span id="lon-<?php echo $i; ?>"><?php echo $geoLocalizacoes[$i]['e']['longitude']; ?></span>
                </li>
                <?php
            }
            ?>
            <li id="size-geo"><?php echo $sizeGeo; ?></li>
                <?php
            }
            ?>
    </ul>
</div>
<?php $this->start('script'); ?>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;libraries=geometry&amp;v=3.13"></script>
<?php echo $this->Html->script("maplace/maplace-0.1.3.min.js"); ?>
<?php echo $this->Html->script("maplace/data/points.js"); ?>
<?php echo $this->Html->script("maplace/data/styles.js"); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var sizeGeo = parseInt($('#size-geo').html());
        var LocsA = [];
        var i = 0;
        for (i = 0; i < sizeGeo; i++) {
            if ($('#lat-' + i).html() !== '0' && $('#lon-' + i).html() !== '0') {
                var lat = parseFloat($('#lat-' + i).html());
                var lon = parseFloat($('#lon-' + i).html());
                var nome = $('#nome-' + i).html();
                var geo = {
                    lat: lat,
                    lon: lon,
                    title: nome,
                    html: '',
                    icon: 'http://maps.google.com/mapfiles/markerA.png',
                    draggable: false

                };
                LocsA[i] = geo;
            }
        }

        var marker = new Maplace({
            locations: LocsA,
            map_div: '#gmap-tabs-1',
            controls_on_map: false,
            show_infowindow: false,
            start: 1,
            zoom: 10,
            afterShow: function (index, location, marker) {
                $('#info').html(location.html);
            }
        });
        marker.Load();
    });
</script>
<?php
$this->end();
