# Not My Cup of Read — Guida Setup e Architettura

## Panoramica

Tema WordPress custom per il progetto editoriale "Not My Cup of Read".
Stack: WordPress 6.4+, PHP 8.1+, Gutenberg-first, ACF (opzionale ma consigliato).

---

## Struttura Cartelle

```
notmycupofread/
├── style.css                    # Dichiarazione tema WordPress
├── theme.json                   # Design tokens (colori, font, spacing)
├── functions.php                # Bootstrap tema
├── header.php                   # Header con nav dark green
├── footer.php                   # Footer con 4 colonne
├── front-page.php               # Homepage editoriale
├── single.php                   # Approfondimento (post standard)
├── single-review.php            # Recensione singola
├── single-podcast_episode.php   # Episodio podcast singolo
├── single-nmcor_event.php       # Evento singolo
├── single-book.php              # Libro singolo
├── single-book_author.php       # Autore singolo
├── archive-review.php           # Archivio recensioni
├── archive-podcast_episode.php  # Archivio episodi
├── archive-nmcor_event.php      # Archivio eventi (timeline)
├── archive-book.php             # Archivio libri
├── page.php                     # Pagina generica
├── index.php                    # Fallback
├── search.php                   # Risultati ricerca
├── 404.php                      # Pagina errore
├── template-podcast.php         # Template: Podcast Landing
├── template-approfondimenti.php # Template: Approfondimenti Landing
├── template-community.php       # Template: Community
├── template-contatti.php        # Template: Contatti
├── demo.html                    # Demo HTML statica (non serve in produzione)
│
├── inc/
│   ├── setup.php                # Theme support, menu, image sizes, widget areas
│   ├── enqueue.php              # CSS/JS enqueue
│   ├── custom-post-types.php    # CPT: review, podcast_episode, nmcor_event, book, book_author
│   ├── custom-taxonomies.php    # Tassonomie: genre, literary_theme, article_type, etc.
│   ├── custom-fields.php        # Meta fields + ACF field groups
│   ├── template-tags.php        # Funzioni template (rating, breadcrumbs, date, etc.)
│   ├── helpers.php              # Query helpers, schema markup, utility
│   ├── gutenberg.php            # Block patterns, block styles
│   └── ajax-handlers.php        # Filtri e load-more AJAX
│
├── template-parts/
│   └── cards/
│       ├── card-review.php      # Card recensione
│       ├── card-article.php     # Card approfondimento
│       ├── card-podcast.php     # Card episodio
│       ├── card-event.php       # Card evento
│       ├── card-book.php        # Card libro (copertina)
│       └── card-author.php      # Card autore
│
├── patterns/
│   ├── hero-editorial.php       # Pattern: Hero sezione editoriale
│   ├── newsletter-signup.php    # Pattern: Newsletter signup
│   └── cta-podcast.php          # Pattern: CTA podcast
│
└── assets/
    ├── css/
    │   ├── main.css             # Stylesheet principale
    │   └── editor.css           # Stili editor Gutenberg
    └── js/
        ├── navigation.js        # Menu mobile, sticky header, search overlay
        ├── main.js              # Reading progress, smooth scroll, animations
        └── filters.js           # Filtri AJAX e Load More
```

---

## Modello Dati

### Post Types

| Post Type         | Slug              | Uso                                |
|-------------------|-------------------|------------------------------------|
| Post (standard)   | `post`            | Approfondimenti (rinominato in admin) |
| Recensione        | `review`          | Recensioni libri                   |
| Episodio Podcast  | `podcast_episode` | Episodi del podcast                |
| Evento            | `nmcor_event`     | Fiere, festival, presentazioni     |
| Libro             | `book`            | Catalogo libri trattati            |
| Autore            | `book_author`     | Profili autori letterari           |

### Tassonomie

| Tassonomia       | Slug             | Applicata a                        |
|------------------|------------------|------------------------------------|
| Genere           | `genre`          | book, review                       |
| Tema Letterario  | `literary_theme` | post, review, book, podcast_episode, book_author |
| Tipo Approfondimento | `article_type` | post                             |
| Format Podcast   | `podcast_format` | podcast_episode                    |
| Tipo Evento      | `event_type`     | nmcor_event                        |
| Editore          | `publisher`      | book                               |

### Relazioni (tramite ACF)

- **Recensione → Libro** (`nmcor_review_book`)
- **Recensione → Episodio** (`nmcor_review_episode`)
- **Episodio → Libri** (`nmcor_episode_books`)
- **Episodio → Autori** (`nmcor_episode_authors`)
- **Evento → Articoli** (`nmcor_event_articles`)
- **Evento → Episodi** (`nmcor_event_episodes`)
- **Libro → Autore** (`nmcor_book_author`)
- **Approfondimento → Libri** (`nmcor_post_books`)
- **Approfondimento → Episodio** (`nmcor_post_episode`)
- **Autore → Libri** (`nmcor_author_books`)

---

## Installazione

### 1. Prerequisiti
- WordPress 6.4+
- PHP 8.1+
- MySQL/MariaDB

### 2. Installazione tema
1. Copia la cartella del tema in `wp-content/themes/notmycupofread/`
2. Vai in **Aspetto → Temi** e attiva "Not My Cup of Read"
3. Il tema registrerà automaticamente CPT, tassonomie e flush dei rewrite rules

### 3. Plugin consigliati
- **Advanced Custom Fields PRO** — Per l'interfaccia relazioni e campi custom (i campi si registrano automaticamente via PHP, non serve import)
- **Yoast SEO** o **Rank Math** — SEO e metadati
- **WP Super Cache** o **W3 Total Cache** — Performance
- **UpdraftPlus** — Backup
- **Contact Form 7** o **WPForms** — Form contatti
- **Mailchimp for WordPress** o **Newsletter** — Gestione newsletter (sostituire i form statici)

### 4. Configurazione iniziale

#### Menu
Vai in **Aspetto → Menu** e crea:
- **Menu Principale** (posizione: "Menu Principale")
  - Home, Recensioni, Approfondimenti, Podcast, Eventi, Libri, Chi siamo, Community

#### Pagine da creare
Crea queste pagine e assegna il template corretto:

| Pagina          | Template                | Slug consigliato    |
|-----------------|-------------------------|---------------------|
| Home            | (usa "Pagina statica")  | —                   |
| Podcast         | Podcast Landing         | `podcast`           |
| Approfondimenti | Approfondimenti Landing | `approfondimenti`   |
| Community       | Community               | `community`         |
| Contatti        | Contatti                | `contatti`          |
| Chi siamo       | (default)               | `chi-siamo`         |
| Privacy Policy  | (default)               | `privacy-policy`    |
| Cookie Policy   | (default)               | `cookie-policy`     |

#### Impostazioni
- **Impostazioni → Lettura**: Pagina statica come home
- **Impostazioni → Permalink**: Struttura personalizzata `/articoli/%postname%/` o `/%postname%/`

---

## Design System

### Palette

| Colore                | Variabile CSS          | Hex       |
|-----------------------|------------------------|-----------|
| Primary (Deep Green)  | `--c-primary`          | `#012d1d` |
| Primary Container     | `--c-primary-container`| `#1B4332` |
| Teal (Action)         | `--c-teal`             | `#23c3bc` |
| Tertiary (Terracotta) | `--c-tertiary`         | `#BC6C4D` |
| Surface (Paper)       | `--c-surface`          | `#fef9f0` |
| Surface Container     | `--c-surface-container`| `#f2ede4` |
| On Surface (Text)     | `--c-on-surface`       | `#1d1c16` |

### Tipografia
- **Headlines**: Newsreader (serif) — Google Fonts
- **Body**: Inter (sans-serif) — Google Fonts

### Regole di design
1. **No 1px borders** — Separare con shift tonali di background
2. **No pure black** — Usare `#1d1c16` per il testo
3. **No angoli a 90°** — Minimo `border-radius: 0.25rem`
4. **Ombre ambient** — Solo `rgba(29, 28, 22, 0.06)`, mai nere

---

## Gestione Contenuti

### Creare una Recensione
1. **Recensioni → Nuova Recensione**
2. Scrivi titolo e contenuto della recensione
3. Nel pannello "Dettagli Recensione" (sidebar ACF):
   - Seleziona il **Libro recensito** (relazione)
   - Inserisci **Valutazione** (1-5)
   - Scrivi **Giudizio sintetico**
   - Collega eventuale **Episodio podcast**
4. Assegna **Genere** e **Temi Letterari**
5. Imposta immagine in evidenza

### Creare un Episodio Podcast
1. **Podcast → Nuovo Episodio**
2. Inserisci titolo, numero episodio, stagione, durata
3. Incolla gli URL: Spotify, YouTube, Apple Podcasts, Embed
4. Collega **Libri trattati** e **Autori trattati**
5. Assegna il **Format** podcast
6. Scrivi le show notes nel contenuto

### Creare un Evento
1. **Eventi → Nuovo Evento**
2. Compila: data inizio/fine, orario, luogo, città
3. Imposta lo **Stato** (In programma / Passato / Annullato)
4. Collega articoli e episodi correlati

### Creare un Libro
1. **Libri → Nuovo Libro**
2. Carica la **copertina** come immagine in evidenza
3. Compila: anno, pagine, ISBN, titolo originale
4. Collega l'**Autore**
5. Assegna **Genere**, **Editore**, **Temi**

---

## Sviluppi Futuri

Il tema è predisposto per:
- **Newsletter**: Sostituire i form statici con Mailchimp/ConvertKit
- **E-commerce / Merchandising**: Aggiungere WooCommerce
- **Membership**: Aggiungere plugin membership per contenuti premium
- **Donazioni**: Integrare Stripe/PayPal per "Supporta il progetto"
- **Multilingua**: Compatibile con WPML/Polylang
- **AMP**: Struttura semantica pronta per AMP
