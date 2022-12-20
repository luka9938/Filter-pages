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
    <h1 id="entry-title">tilbehør</h1>
    <section id="primary" class="content-area spil entry">
        <main id="main" class="site-main entry-content">
            <nav id="filtrering" class="alignfull">
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
                    <h2 class="pris">
                        </p>
                </section>
            </article>
        </template>
        <script>
            const siteUrl = "<?php echo esc_url(home_url('/')); ?>";
            let tilbehor = [];
            let categories = [];
            let indhold = [];
            let liste;
            let skabelon;
            let filterspil = "alle";
            document.addEventListener("DOMContentLoaded", start);

            function start() {
                liste = document.querySelector("#spil-oversigt");
                skabelon = document.querySelector("template");
                getJson();
            }

            async function getJson() {
                // Fetch all custom post types with the slug "tilbehor"
                const url = "https://vijasan.dk/kea/10_eksamen/nintendopusheren/wp-json/wp/v2/tilbehor?per_page=100";
                // Fetch basic categories
                const catUrl = "https://vijasan.dk/kea/10_eksamen/nintendopusheren/wp-json/wp/v2/categories?per_page=100";
                let response = await fetch(url);
                let catResponse = await fetch(catUrl);
                tilbehor = await response.json();
                categories = await catResponse.json();
                displayGames();
                createButtons();
            }

            function createButtons() {
                categories.forEach(cat => {
                    //console.log(cat.id);
                    if (cat.name == "Nintendo" || cat.name == "Sega" || cat.name == "PlayStation" || cat.name == "Xbox" || cat.name == "Handheld") {
                        document.querySelector("#filtrering").innerHTML += `<button class="filter" data-tilbehor="${cat.id}">${cat.name}</button>`
                    }
                })
                addEventListenersToButtons();
            }

            function displayGames() {
                console.log(tilbehor);
                liste.innerHTML = "";
                console.log({ filterspil });
                tilbehor.forEach(tilbehor => {
                    // Check filterspil
                    if (filterspil != "alle" && !tilbehor.categories.includes(parseInt(filterspil)))
                        return;
                    const clone = skabelon.cloneNode(true).content;
                    clone.querySelector(".spil-title").textContent = tilbehor.title.rendered;
                    clone.querySelector(".pris").textContent = tilbehor.pris;
                    clone.querySelector(".konsol").textContent = tilbehor.konsol;
                    clone.querySelector(".picture").src = tilbehor.billede;
                    clone.querySelector(".picture").alt = tilbehor.billednavn;
                    clone.querySelector(".spil-container").addEventListener("click", () => visDetaljer(tilbehor));
                    liste.appendChild(clone);
                });
            }

            function visDetaljer(tilbehor) {
                popup.style.display = "flex";
                popup.querySelector(".spil-title").textContent = tilbehor.title.rendered;
                popup.querySelector(".pris").textContent = tilbehor.pris;
                popup.querySelector(".konsol").textContent = tilbehor.konsol;
                popup.querySelector(".picture").src = tilbehor.billede;
                popup.querySelector(".picture").alt = tilbehor.billednavn;
            }

            document.querySelector("#luk").addEventListener("click", () => (popup.style.display = "none"));

            function addEventListenersToButtons() {
                document.querySelectorAll(".filter").forEach(btn => {
                    btn.addEventListener("click", filter);
                });
            }

            function filter() {
                filterspil = this.dataset.tilbehor
                if (this.dataset.tilbehor === undefined) {
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