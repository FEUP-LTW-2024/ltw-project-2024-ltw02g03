<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/user.class.php');

function drawProfile(Session $session,$db) { ?>   
    <main id="profilepage">
        <div id="profile-img-infos">
            <img src="/Docs/img/9024845_user_circle_light_icon.png" alt="" height="100">
            <div>
                <h1>John Doe</h1>
                <h2>Portugal, GUarda, Guarda</h2>
            </div>

        </div>
        <div>
            b
        </div>
        <div>
            c
        </div>
    </main>
<?php } ?>
