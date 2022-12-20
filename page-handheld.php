<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */
get_header();
?>

<body class="spil-bg">
    <h1 id="entry-title">handheld</h1>
    <section id="primary" class="content-area spil entry">
        <main id="main" class="site-main entry-content">
            <nav id="filtrering" class="alignfull">
                <label for="emballage" class="emballage-label">Emballage</label>
                <select name="emballage" id="emballage" onchange="filterEmballage(this)">
                    <option value="alt">Alt</option>
                    <option value="Med">Med</option>
                    <option value="Uden">Uden</option>
                </select>
                <button class="filter valgt" data-spil="alle">Alle</button>
            </nav>
            <section id="popup">
                <article id="popup-article">
                    <div id="luk"></div>
                    <section class="popup-1"><img class="picture" src="" alt="" /></section>
                    <section class="popup-2">
                        <h2 class="konsol"></h2>
                        <h1 class="spil-title"></h1>
                        <h2 class="pris"></h2>
                        <h3 class="emballage popup-emballage"></h3>
                        <div class="popup-buttons">
                            <button class="btn popup-kurv">Tilføj til kurv</button>
                            <button class="btn popup-favorite"><img src="/img/star2.png" alt=""></button>
                        </div>
                        <a href="#">tjek om det er tilgængeligt i butikken</a>
                    </section>
                </article>
            </section>
            <article id="spil-oversigt" class="alignwide"></article>
        </main>
        <template>
            <article class="spil-container">
                <section class="menu-1"><img class="picture" src="" alt="" /></section>
                <section class="menu-2">
                    <h2 class="spil-title"></h2>
                    <p class="konsol"></p>
                    <p class="emballage"></p>
                    <h2 class="pris">
                        </p>
                </section>
            </article>
        </template>
        <script>
            const siteUrl = "<?php echo esc_url(home_url('/')); ?>";
            let handheld = [];
            let categories = [];
            let indhold = [];
            let liste;
            let skabelon;
            let filterspil = "alle";
            let filterselectedemballage = "alt";
            document.addEventListener("DOMContentLoaded", start);

            function start() {
                liste = document.querySelector("#spil-oversigt");
                skabelon = document.querySelector("template");
                getJson();
            }

            function filterEmballage(target) {
                filterselectedemballage = target.value;
                displayGames();
            }

            async function getJson() {
                // Fetch all custom post types with the slug "handheld"
                const url = "https://vijasan.dk/kea/10_eksamen/nintendopusheren/wp-json/wp/v2/handheld?per_page=100";
                // Fetch basic categories
                const catUrl = "https://vijasan.dk/kea/10_eksamen/nintendopusheren/wp-json/wp/v2/categories?per_page=100";
                let response = await fetch(url);
                let catResponse = await fetch(catUrl);
                handheld = await response.json();
                categories = await catResponse.json();
                displayGames();
                createButtons();
            }

            function createButtons() {
                categories.forEach(cat => {
                    //console.log(cat.id);
                    if (cat.name == "Gameboy" || cat.name == "Gameboy Color" || cat.name == "Gameboy Advance" || cat.name == "Nintendo DS" || cat.name == "Nintendo 3DS" || cat.name == "PSP" || cat.name == "PS Vita" || cat.name == "Game Gear") {
                        document.querySelector("#filtrering").innerHTML += `<button class="filter" data-handheld="${cat.id}">${cat.name}</button>`
                    }
                })
                addEventListenersToButtons();
            }

            function displayGames() {
                console.log(handheld);
                console.log(filterselectedemballage);
                liste.innerHTML = "";
                console.log({ filterspil });
                handheld.forEach(handheld => {
                    // Check filterspil
                    if (filterspil != "alle" && !handheld.categories.includes(parseInt(filterspil)))
                        return;
                    if (filterselectedemballage != "alt" && handheld.emballage != filterselectedemballage)
                        return;
                    const clone = skabelon.cloneNode(true).content;
                    clone.querySelector(".spil-title").textContent = handheld.title.rendered;
                    clone.querySelector(".pris").textContent = handheld.pris;
                    clone.querySelector(".konsol").textContent = handheld.konsol;
                    clone.querySelector(".emballage").textContent = handheld.emballage + " emballage";
                    clone.querySelector(".picture").src = handheld.billede;
                    clone.querySelector(".picture").alt = handheld.billednavn;
                    clone.querySelector(".spil-container").addEventListener("click", () => visDetaljer(handheld));
                    liste.appendChild(clone);
                });
            }

            function visDetaljer(handheld) {
                popup.style.display = "flex";
                popup.querySelector(".spil-title").textContent = handheld.title.rendered;
                popup.querySelector(".pris").textContent = handheld.pris;
                popup.querySelector(".konsol").textContent = handheld.konsol;
                popup.querySelector(".emballage").textContent = handheld.emballage + " emballage";
                popup.querySelector(".picture").src = handheld.billede;
                popup.querySelector(".picture").alt = handheld.billednavn;
            }

            document.querySelector("#luk").addEventListener("click", () => (popup.style.display = "none"));

            function addEventListenersToButtons() {
                document.querySelectorAll(".filter").forEach(btn => {
                    btn.addEventListener("click", filter);
                });
            }

            function filter() {
                filterspil = this.dataset.handheld
                if (this.dataset.handheld === undefined) {
                    filterspil = "alle"
                }
                document.querySelector("h1").textContent = this.textContent;
                document.querySelectorAll("#filtrering .filter").forEach(elm => {
                    elm.classList.remove("valgt");
                });
                this.classList.add("valgt");
                displayGames();
            }
        </script>
</body>
<?php
get_footer();
?>