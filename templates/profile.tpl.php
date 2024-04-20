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
            <div id="profilepage-name-loc">
                <h1>John Doe</h1>
                <h2>Portugal, Guarda, Guarda</h2>
            </div>

        </div>
        <div id="left-profile-page">
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        <div>
            <h1>Presented Products</h1>
            <div id="profile-page-items">
                <article class="profilepage-item">
                    <img class="profilepage-img-item" src="../Docs/samsung-galaxy-s20-fe-5g-g781-128gb-dual-sim-lavanda.jpg" alt="" width="100">
                    <div>
                        <h1>TV 140hz 1ms QHD</h1>
                        <h2>140.00€</h2>
                    </div>
                
                </article>
                <article class="profilepage-item">
                    <img class="profilepage-img-item" src="../Docs/samsung-galaxy-s20-fe-5g-g781-128gb-dual-sim-lavanda.jpg" alt="" width="100">
                    <div>
                        <h1>TV 140hz 1ms QHD</h1>
                        <h2>140.00€</h2>
                    </div>
                
                </article>
                <article class="profilepage-item">
                    <img class="profilepage-img-item" src="../Docs/samsung-galaxy-s20-fe-5g-g781-128gb-dual-sim-lavanda.jpg" alt="" width="100">
                    <div>
                        <h1>TV 140hz 1ms QHD</h1>
                        <h2>140.00€</h2>
                    </div>
                
                </article>
                <article class="profilepage-item">
                    <img class="profilepage-img-item" src="../Docs/samsung-galaxy-s20-fe-5g-g781-128gb-dual-sim-lavanda.jpg" alt="" width="100">
                    <div>
                        <h1>TV 140hz 1ms QHD</h1>
                        <h2>140.00€</h2>
                    </div>
                
                </article>
                <article class="profilepage-item">
                    <img class="profilepage-img-item" src="../Docs/samsung-galaxy-s20-fe-5g-g781-128gb-dual-sim-lavanda.jpg" alt="" width="100">
                    <div>
                        <h1>TV 140hz 1ms QHD</h1>
                        <h2>140.00€</h2>
                    </div>
                
                </article>


               
                
               
            </div>
        </div>
    </main>
<?php } ?>
