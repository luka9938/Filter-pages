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
    <h1 id="entry-title" class="has-text-align-center">Nintendo</h1>
    <section id="primary" class="content-area spil entry">
        <main id="main" class="site-main entry-content">
            <nav id="filtrering" class="alignfull">
                <label for="emballage">Emballage</label>
                <select name="emballage" id="emballage" aria-label="default" onchange="filterEmballage(this)">
                    <option value="alt">Alt</option>
                    <option value="Med">Med</option>
                    <option value="Uden">Uden</option>
                </select>
                <button class="filter valgt" data-spil="alle">Alle</button>
            </nav>

            <article id="spil-oversigt" class="alignwide"></article>
        </main>
        <template>
            <section class="spil-container">
                <h2></h2>
                <img src="" alt="">
                <p class="pris"></p>
                <p class="konsol"></p>
                <p class="emballage"></p>
            </section>
        </template>
    </section>
    <script>
        const siteUrl = "<?php echo esc_url(home_url('/')); ?>";
        let nintendo = [];
        let categories = [];
        let indhold = [];
        let liste;
        let skabelon;
        let filterspil = "alle";
        let filterselectedemballage = "alt";
        document.addEventListener("DOMContentLoaded", start);

        function start() {

            console.log("id er", <?php echo get_the_ID() ?>);
            liste = document.querySelector("#spil-oversigt");
            skabelon = document.querySelector("template");
            getJson();
        }

        function filterEmballage(target) {
            filterselectedemballage = target.value;
            displayGames();
        }

        async function getJson() {
            // Fetch all custom post types with the slug "nintendo"
            const url = siteUrl + "wp-json/wp/v2/nintendo?per_page=100";
            // Fetch basic categories
            const catUrl = siteUrl + "wp-json/wp/v2/categories?per_page=100";
            let response = await fetch(url);
            let catResponse = await fetch(catUrl);
            nintendo = await response.json();
            categories = await catResponse.json();
            displayGames();
            createButtons();
        }

        function createButtons() {
            categories.forEach(cat => {
                //console.log(cat.id);
                if (cat.name == "NES" || cat.name == "Super Nintendo" || cat.name == "Nintendo 64" || cat.name == "Gamecube" || cat.name == "Wii" || cat.name == "Wii U" || cat.name == "Switch") {
                    document.querySelector("#filtrering").innerHTML += `<button class="filter" data-nintendo="${cat.id}">${cat.name}</button>`
                }
            })
            addEventListenersToButtons();
        }

        function displayGames() {
            console.log(nintendo);
            console.log(filterselectedemballage);
            liste.innerHTML = "";
            console.log({ filterspil });
            nintendo.forEach(nintendo => {
                // Check filterspil
                if (filterspil != "alle" && !nintendo.categories.includes(parseInt(filterspil)))
                    return;
                if (filterselectedemballage != "alt" && nintendo.emballage != filterselectedemballage)
                    return;
                const clone = skabelon.cloneNode(true).content;
                clone.querySelector("h2").textContent = nintendo.title.rendered;
                // Display the "pris" custom field
                clone.querySelector(".pris").textContent = nintendo.pris;
                clone.querySelector(".konsol").textContent = nintendo.konsol;
                clone.querySelector(".emballage").textContent = nintendo.emballage;
                clone.querySelector("img").src = nintendo.billede;
                klon.querySelector(".article").addEventListener("click", () => visDetaljer(toj));
                liste.appendChild(clone);
            });
        }

        function addEventListenersToButtons() {
            document.querySelectorAll(".filter").forEach(btn => {
                btn.addEventListener("click", filter);
            });
        }

        function filter() {
            filterspil = this.dataset.nintendo
            if (this.dataset.nintendo === undefined) {
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