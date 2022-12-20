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
    <h1 id="entry-title">Sega</h1>
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
            let sega = [];
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
                // Fetch all custom post types with the slug "sega"
                const url = "https://vijasan.dk/kea/10_eksamen/nintendopusheren/wp-json/wp/v2/sega?per_page=100";
                // Fetch basic categories
                const catUrl = "https://vijasan.dk/kea/10_eksamen/nintendopusheren/wp-json/wp/v2/categories?per_page=100";
                let response = await fetch(url);
                let catResponse = await fetch(catUrl);
                sega = await response.json();
                categories = await catResponse.json();
                displayGames();
                createButtons();
            }

            function createButtons() {
                categories.forEach(cat => {
                    //console.log(cat.id);
                    if (cat.name == "Sega Master System" || cat.name == "Sega Mega Drive" || cat.name == "Sega Mega CD" || cat.name == "32X" || cat.name == "Saturn" || cat.name == "Dreamcast") {
                        document.querySelector("#filtrering").innerHTML += `<button class="filter" data-sega="${cat.id}">${cat.name}</button>`
                    }
                })
                addEventListenersToButtons();
            }

            function displayGames() {
                console.log(sega);
                console.log(filterselectedemballage);
                liste.innerHTML = "";
                console.log({ filterspil });
                sega.forEach(sega => {
                    // Check filterspil
                    if (filterspil != "alle" && !sega.categories.includes(parseInt(filterspil)))
                        return;
                    if (filterselectedemballage != "alt" && sega.emballage != filterselectedemballage)
                        return;
                    const clone = skabelon.cloneNode(true).content;
                    clone.querySelector(".spil-title").textContent = sega.title.rendered;
                    clone.querySelector(".pris").textContent = sega.pris;
                    clone.querySelector(".konsol").textContent = sega.konsol;
                    clone.querySelector(".emballage").textContent = sega.emballage + " emballage";
                    clone.querySelector(".picture").src = sega.billede;
                    clone.querySelector(".picture").alt = sega.billednavn;
                    clone.querySelector(".spil-container").addEventListener("click", () => visDetaljer(sega));
                    liste.appendChild(clone);
                });
            }

            function visDetaljer(sega) {
                popup.style.display = "flex";
                popup.querySelector(".spil-title").textContent = sega.title.rendered;
                popup.querySelector(".pris").textContent = sega.pris;
                popup.querySelector(".konsol").textContent = sega.konsol;
                popup.querySelector(".emballage").textContent = sega.emballage + " emballage";
                popup.querySelector(".picture").src = sega.billede;
                popup.querySelector(".picture").alt = sega.billednavn;
            }

            document.querySelector("#luk").addEventListener("click", () => (popup.style.display = "none"));

            function addEventListenersToButtons() {
                document.querySelectorAll(".filter").forEach(btn => {
                    btn.addEventListener("click", filter);
                });
            }

            function filter() {
                filterspil = this.dataset.sega
                if (this.dataset.sega === undefined) {
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