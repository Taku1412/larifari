<?php
function renderOffers($pdo, $offers) {
    $result = [];
    foreach ($offers as $offer) {
            $result[] =
                "<p class='offer_p'>
                    <div class='offer_title'>$offer[title]</div>
                    <div class='offer_author'>$offer[author]</div>
                    <div class='offer_offerer'>$offer[offerer]</div>
                    <div class='offer_price'>$offer[price]</div>
                    <div class='offer_picture'>$offer[picture]</div>
                    <div class='offer_detail_link'><a href='main.php?page=details&offer=offer[oID]'>Details</a></div>".
                    ($pdo->query("SELECT COUNT(*) FROM watch_list WHERE offer = $offer[oID] AND nickname = '$_SESSION[username]'")->fetchColumn() >= 1 ?
                    "<div class='offer_favorite_active'><input type='button' onclick='unfavoriteOffer(".'"'.$_SESSION['username'].'"'.", $offer[oID])' value='Unfav'></div>" :
                    "<div class='offer_favorite_not_active'><input type='button' onclick='favoriteOffer(".'"'.$_SESSION['username'].'"'.", $offer[oID])' value='Fav'></div>").
                "</p>";
    }
    return $result;
}
?>