<?php
/**
 * Title: Newsletter Signup
 * Slug: nmcor/newsletter-signup
 * Categories: nmcor-cta
 * Description: Blocco iscrizione newsletter con sfondo tonale.
 */
?>
<!-- wp:group {"style":{"color":{"background":"#e6e1ce"},"spacing":{"padding":{"top":"3rem","bottom":"3rem","left":"3rem","right":"3rem"}},"border":{"radius":"0.75rem"}},"layout":{"type":"constrained","contentSize":"460px"}} -->
<div class="wp-block-group has-background" style="background-color:#e6e1ce;border-radius:0.75rem;padding-top:3rem;padding-bottom:3rem;padding-left:3rem;padding-right:3rem">
	<!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"}}} -->
	<h3 class="wp-block-heading has-text-align-center" style="font-style:normal;font-weight:700">Resta aggiornato</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#4a5568"}}} -->
	<p class="has-text-align-center" style="color:#4a5568">Ricevi le nostre selezioni editoriali ogni settimana nella tua inbox.</p>
	<!-- /wp:paragraph -->

	<!-- wp:html -->
	<div class="newsletter-form" style="max-width:460px;margin:0 auto;">
		<input type="email" placeholder="La tua email" style="flex:1;padding:0.75rem 1.25rem;border:none;border-radius:0.5rem;background:#ddd8c5;font-size:0.9375rem;">
		<button class="btn btn--teal btn--small">Iscriviti</button>
	</div>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
