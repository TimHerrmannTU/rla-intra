<table class="firmen-liste w-100">
    <thead>
        <tr>
            <th style="text-align: right"><?= ($custom_type == 'firma') ? "Gewerk" : "Produktart" ?></th>
            <th>Firmenname</th>
            <th>Bundesland</th>
            <th colspan="3">Adresse & Kontakt</th>
            <th>Website</th>
            <th>Firmengröße</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        while( $the_query->have_posts() ) : 
            $the_query->the_post();

            $tags = array();
            if (get_field("gewerk")) {
                $tags = array_merge($tags, get_field("gewerk"));
            }
            if (get_field("produktkategorie")) {
                $tags = array_merge($tags, get_field("produktkategorie"));
            }
            $tag_string = ($tags) ? implode(", ", $tags) : "";

            ?>
            <tr tag="<?= $tag_string ?>" ort="<?= esc_html(get_field('ort')) ?>" plz="<?= esc_html(get_field('plz')) ?>" bundesland="<?= esc_html(get_field('bundesland')) ?>">
                <td f-name="tag" style="display: none"><?= $tag_string ?></td>
                <td class="tag-cloud">
                    <?php 
                    $hidden_tags = array();
                    $breakpoint = 2;
                    if (count($tags) > $breakpoint && count($tags) != $breakpoint+1) {
                        $hidden_tags = array_slice($tags, 2);
                    }
                    foreach ($tags as $tag) {
                        if (in_array($tag, $hidden_tags)) {
                            ?><button class="filter-button bordered smooth hidden"><?= $tag ?></button><?php
                        }
                        else {
                            ?><button class="filter-button bordered smooth"><?= $tag ?></button><?php
                        }
                    }
                    if (count($hidden_tags) > 1) {
                        ?>
                        <button 
                            class="filter-button bordered smooth more"
                            onclick="$(this).closest('td').find('.hidden').toggleClass('hidden'); $(this).toggle()">
                            Weitere +
                        </button><?php
                    }
                    ?>
                </td>
                <td f-name="name">
                    <a href="<?= the_permalink() ?>" target="_blank"><b><?php the_title(); ?></b></a>
                </td>
                <td f-name="bundesland"><?= esc_html(get_field('bundesland')) ?></td>
                <td f-name="plz"><?= esc_html(get_field('plz')) ?></td>
                <td f-name="ort"><?= esc_html(get_field('ort')) ?></td>
                <td class="dd-wrapper">
                    <a class="tooltip" onclick="pop_up(this); $(this).find('.icon-down-open-2').toggleClass('up')" data-tooltip="Ganze Adresse anzeigen...">
                        <span class="icon-down-open-2"></span>
                    </a>
                    <ul class="dd hidden">
                        <li>
                            <?php
                            $full_adress = get_field('strasse').", ".get_field('plz')." ".get_field('ort');
                            $google_maps_link  = "https://www.google.com/maps/search/?api=1&query=";
                            $google_maps_link .= get_field("bundesland")."+";
                            $google_maps_link .= get_field("plz")."+";
                            $google_maps_link .= get_field("ort")."+";
                            $google_maps_link .= get_field("strasse")."+";
                            $google_maps_link .= str_replace(" ", "+", get_the_title());
                            ?>
                            <b>Addresse:</b><br>
                            <span f-name="full-adress"><?= $full_adress ?></span><br>
                            <a href="<?= $google_maps_link ?>" target="_blank" style="color:var(--activeColor)">google maps<span class="dashicons dashicons-external"></span></a>
                        </li>
                        <li><b>Telefon: </b><a f-name="phone" href="tel: <?= str_replace(" ", "", get_field('telefon')) ?>"><?= esc_html(get_field('telefon')); ?></a></li>
                        <li><b>Email: </b><a f-name="e-mail" href="mailto: <?= get_field('mail')?>"><?= get_field('mail'); ?></a></li>
                    </ul>
                </td>
                <td f-name="website"><a href="<?= esc_html(get_field('website')); ?>"><?= esc_html(get_field('website')); ?></a></td>
                <td f-name="leistungsfahigkeit"><?= esc_html(get_field('leistungsfahigkeit')); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<style>
.firmen-liste {
    border-collapse: collapse;
    background-color: var(--secondBg);
}
.firmen-liste thead tr {
    background-color: rgb(100, 100, 100);
    color: white;
}
.firmen-liste tbody tr {
    border-bottom: 1px solid rgb(100, 100, 100);
}
.firmen-liste td,
.firmen-liste th {
    padding: 5px 10px !important;
}
.firmen-liste thead th {
    font-weight: 300;
    letter-spacing: 2px;
}
.firmen-liste tbody td.tag-cloud {
    height: 100%;
    width: 220px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 5px;
}
.firmen-liste tbody td[f-name="website"] {
    max-width: 300px;
    overflow-x: hidden;
}
.firmen-liste .dd-wrapper {
    position: relative;
}
.firmen-liste .dd-wrapper .dd {
    position: absolute;
    right: 0px;
    padding: 3px 10px;
    width: max-content;
    background: wheat;
    z-index: 1;
}
.firmen-liste .dd-wrapper .dd li {
    margin: 5px 0px;
}
.firmen-liste button.more {
    background-color: black;
    color: white;
}

a.tooltip:after {
  content: attr(data-tooltip);
  background: white;
  color: black;
  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
  position: absolute;
  top: 35px;
  left: -180px;
  padding: 5px 10px;
  border-radius : 3px;
  white-space: nowrap;
  opacity: 0;
  transition : all 0.4s ease;
}
td:not(.active) a.tooltip:hover:after, a:hover:before {
  opacity: 1;
}
</style>