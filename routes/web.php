<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Models\LienDisCatTarBookingField;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CritereController;
use App\Http\Controllers\MentionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\AdminBlogTagController;
use App\Http\Controllers\PanierPaymentController;
use App\Http\Controllers\StructureUserController;
use App\Http\Controllers\AdminStructureController;
use App\Http\Controllers\AdminTarifTypeController;
use App\Http\Controllers\StructureTarifController;
use App\Http\Controllers\AdminDisciplineController;
use App\Http\Controllers\StructureGestionController;
use App\Http\Controllers\FamilleDisciplineController;
use App\Http\Controllers\StructureAddresseController;
use App\Http\Controllers\StructureCatTarifController;
use App\Http\Controllers\StructurePlanningController;
use App\Http\Controllers\ProductReservationController;
use App\Http\Controllers\StructureCategorieController;
use App\Http\Controllers\DisciplineSimilaireController;
use App\Http\Controllers\LienDisCatTariftypeController;
use App\Http\Controllers\ReservationPlanningController;
use App\Http\Controllers\StructureDisciplineController;
use App\Http\Controllers\StructureStatistiqueController;
use App\Http\Controllers\Discipline\DisciplineController;
use App\Http\Controllers\AdminTarifTypeAttributController;
use App\Http\Controllers\LienDisCatCritValSsCritController;
use App\Http\Controllers\LienDisCatTarAttrValeurController;
use App\Http\Controllers\AdminCategorieDisciplineController;
use App\Http\Controllers\StructureActiviteProduitController;
use App\Http\Controllers\CategoryDisciplineCritereController;
use App\Http\Controllers\LienDisCatTarAttrSousAttrController;
use App\Http\Controllers\LienDisCatTarBookingFieldController;
use App\Http\Controllers\StructurePlanningMultipleController;
use App\Http\Controllers\LienDisCatTariftypeAttributController;
use App\Http\Controllers\Discipline\CategoryDisciplineController;
use App\Http\Controllers\LienDisCatCritValSsCritValeurController;
use App\Http\Controllers\CategoryDisciplineCritereValeurController;
use App\Http\Controllers\LienDisCatTarAttrSousAttrValeurController;
use App\Http\Controllers\LienDisCatTarBookingFieldValeurController;
use App\Http\Controllers\LienDisCatTarBookingFieldSousFieldController;
use App\Http\Controllers\LienDisCatTarBookingFieldSsFieldValeurController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])
    ->name('welcome');

Route::get('/mentions', [MentionController::class, 'index'])->name('mentions.index');

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::get('/favoris', [FavoritesController::class, 'index'])->name(
    'favoris.index'
);


// Panier et réservations
Route::get('/panier', [PanierController::class, 'index'])->name(
    'panier.index'
);
Route::post('/panier', [PanierController::class, 'store'])->name(
    'panier.store'
);
Route::post('/reservations', [ProductReservationController::class, 'store'])->name('reservations.store');
Route::delete('/panier/{reservation}', [PanierController::class, 'destroy'])->name(
    'panier.destroy'
);
Route::delete('/panier/{reservation}/plannings/{planning}', [ReservationPlanningController::class, 'update'])->name(
    'reservations.plannings.update'
);
Route::delete('/panier/{reservation}/plannings/{planning}', [ReservationPlanningController::class, 'destroy'])->name(
    'reservations.plannings.destroy'
);

Route::stripeWebhooks('stripe/webhook');

//Blog
Route::get('/blog/articles/{discipline?}', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::post('/blog/{post:slug}/likes', [PostLikeController::class, 'store'])->name('posts.likes.store');

// structures route
Route::get('/structures', [StructureController::class, 'index'])
        ->name('structures.index');

Route::get('str-{structure:slug}', [StructureController::class, 'show'])
    ->name('structures.show');

// familles routes
require __DIR__ . '/famille.php';

// disciplines routes
require __DIR__ . '/discipline.php';

// activites route
Route::get('/activites-{activite}-{slug}', [ActiviteController::class, 'show'])->name('structures.activites.show');

// Departements routes
require __DIR__ . '/departement.php';

Route::middleware(['auth', 'verified'])->group(function () {
    //blog create, conflits routes avec slug && disciplines
    Route::get('/blog/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/blog', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/blog/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/blog/{post:slug}/comments', [PostCommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/blog/{post:slug}/comments/{comment}', [PostCommentController::class, 'destroy'])->name('posts.comments.destroy');

    //Panier, coordonnées et paiement
    Route::get('/panier/coordonnees/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/panier/coordonnees/store', [CustomerController::class, 'store'])->name('customers.store');

    Route::get('/panier/paiement', [PanierPaymentController::class, 'index'])->name('panier.paiement.index');
    Route::get('/panier/paiement/success', [PanierPaymentController::class, 'success'])->name('panier.paiement.success');
    Route::get('/panier/paiement/cancel', [PanierPaymentController::class, 'cancel'])->name('panier.paiement.cancel');

    //structures
    Route::get('/structures/create', [StructureController::class, 'create'])->name('structures.create');
    Route::post('/structures', [StructureController::class, 'store'])->name('structures.store');
    Route::get('/structures/{structure:slug}/edit', [StructureController::class, 'edit'])->name('structures.edit');
    Route::put('/structures/{structure}', [StructureController::class, 'update'])->name('structures.update');
    Route::delete('/structures/{structure}', [StructureController::class, 'destroy'])->name('structures.destroy');

    Route::get('/gestion/{structure:slug}', [StructureGestionController::class, 'index'])->name('structures.gestion.index');
    Route::get('/gestion/{structure:slug}/reservations', [ProductReservationController::class, 'index'])->name('structures.gestion.reservations.index');
    Route::put('/gestion/{structure}/reservations/mark-reservation-as-read', [ProductReservationController::class, 'markAsRead'])->name('structures.gestion.reservations.markAsRead');
    Route::put('/gestion/{structure:slug}/reservations/{reservation}', [ProductReservationController::class, 'update'])->name('structures.gestion.reservations.update');
    Route::get('/gestion/{structure:slug}/statistiques', [StructureStatistiqueController::class, 'index'])->name('structures.gestion.statistiques.index');

    Route::post('/structures/{structure:slug}/{discipline:slug}/newactivitystore', [ActiviteController::class, 'newactivitystore'])->name('structures.activites.newactivitystore');
    Route::patch('/structures/{structure:slug}/activites/{activite:id}/toggleactif', [ActiviteController::class, 'toggleactif'])->name('structures.activites.toggleactif');
    Route::resource('structures.activites', ActiviteController::class)->scoped(['structure' => 'slug','activite' => 'id'])->except(['index', 'show', 'create', 'edit']);

    Route::post('/structures/{structure:slug}/activites/{activite:id}/produits/{produit:id}/duplicate', [StructureActiviteProduitController::class, 'duplicate'])->name('produits.duplicate');
    Route::resource('structures.activites.produits', StructureActiviteProduitController::class)->scoped(['structure' => 'slug', 'activite' => 'id', 'produit' => 'id']);

    Route::post('/structures/{structure:slug}/tarifs/{tarif:id}/produits/{produit:id}/duplicate', [StructureTarifController::class, 'duplicate'])->name('tarifs.duplicate');

    // First version for tarifs
    Route::post('structures/{structure:slug}/tarifs', [StructureTarifController::class, 'store'])->name('structures.tarifs.store');
    Route::put('structures/{structure:slug}/tarifs/{tarif}', [StructureTarifController::class, 'update'])->name('structures.tarifs.update');
    Route::delete('/structures/{structure:slug}/tarifs/{tarif:id}/produits/{produit:id}', [StructureTarifController::class, 'destroy'])->name('tarifs.destroy');
    Route::delete('/structures/{structure:slug}/tarifs/{tarif:id}', [StructureTarifController::class, 'destroyTarif'])->name('tarifs.destroyTarif');

    // Second version for tarifs
    Route::post('structures/{structure:slug}/tarifswithattributs', [StructureCatTarifController::class, 'store'])->name('structures.cat.tarifs.store');
    Route::put('structures/{structure:slug}/tarifswithattributs/{tarif}', [StructureCatTarifController::class, 'update'])->name('structures.cat.tarifs.update');
    Route::delete('/structures/{structure:slug}/tarifswithattributs/{tarif}', [StructureCatTarifController::class, 'destroy'])->name('structures.cat.tarifs.destroy');

    Route::post('structures/{structure:slug}/plannings', [StructurePlanningController::class, 'store'])->name('structures.plannings.store');
    Route::put('structures/{structure:slug}/plannings/{planning}', [StructurePlanningController::class, 'update'])->name('structures.plannings.update');
    Route::delete('structures/{structure:slug}/plannings/{planning}', [StructurePlanningController::class, 'destroy'])->name('structures.plannings.destroy');

    Route::post('structures/{structure:slug}/plannings/multiples', [StructurePlanningMultipleController::class, 'store'])->name('structures.plannings.multiples.store');

    Route::get('str-{structure:slug}/disciplines', [StructureDisciplineController::class, 'index'])->name('structures.disciplines.index');
    Route::get('str-{structure:slug}/dis-{discipline:slug}', [StructureDisciplineController::class, 'show'])->name('structures.disciplines.show');
    Route::delete('structures/{structure}/disciplines/{discipline}', [StructureDisciplineController::class, 'destroy'])->name('structures.disciplines.destroy');

    Route::get('str-{structure:slug}/dis-{discipline:slug}/cat-{categorie:id}', [StructureCategorieController::class, 'show'])->name('structures.categories.show');

    Route::delete('structures/{structure}/categories/{categorie}', [StructureCategorieController::class, 'destroy'])->name('structures.categories.destroy');

    Route::post('structures/{structure:slug}/adresses', [StructureAddresseController::class, 'store'])->name('structures.adresses.store');
    Route::put('structures/{structure:slug}/adresses/{adress}', [StructureAddresseController::class, 'update'])->name('structures.adresses.update');
    Route::delete('structures/{structure:slug}/adresses/{adress}', [StructureAddresseController::class, 'destroy'])->name('structures.adresses.destroy');

    Route::post('structures/{structure:slug}/partenaires', [StructureUserController::class, 'store'])->name('structures.partenaires.store');
    Route::put('structures/{structure:slug}/partenaires/{partenaire}', [StructureUserController::class, 'update'])->name('structures.partenaires.update');
    Route::delete('structures/{structure:slug}/partenaires/{partenaire}', [StructureUserController::class, 'destroy'])->name('structures.partenaires.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');

        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::patch('/categories/{categorie}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{categorie}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('/categories/{categorie}/disciplines', [AdminCategorieDisciplineController::class, 'store'])->name('admin.categories.disciplines.store');

        Route::get('/utilisateurs', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/structures', [AdminStructureController::class, 'index'])->name('admin.structures.index');
        Route::get('/disciplines', [AdminDisciplineController::class, 'index'])->name('admin.disciplines.index');
        Route::get('/tarifs', [AdminTarifTypeController::class, 'index'])->name('admin.tarifs.index');

        Route::get('/blog', [AdminBlogController::class, 'index'])->name('admin.blog.index');
        Route::get('/blog/tags', [AdminBlogTagController::class, 'index'])->name('admin.blog.tags.index');
        Route::post('/blog/tags', [AdminBlogTagController::class, 'store'])->name('admin.blog.tags.store');
        Route::delete('/blog/tags/{tag}', [AdminBlogTagController::class, 'destroy'])->name('admin.blog.tags.destroy');

        Route::post('/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store');
        Route::patch('/roles/{role}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy');

        Route::get('/disciplines/dis-{discipline:slug}', [AdminDisciplineController::class, 'edit'])->name('admin.disciplines.edit');

        Route::get('/disciplines/dis-{discipline:slug}/informations', [DisciplineController::class, 'edit'])->name('admin.disciplines.informations.edit');
        Route::get('/disciplines/dis-{discipline:slug}/familles', [FamilleDisciplineController::class, 'edit'])->name('admin.disciplines.familles.edit');
        Route::get('/disciplines/dis-{discipline:slug}/similaires', [DisciplineSimilaireController::class, 'edit'])->name('admin.disciplines.similaires.edit');
        Route::get('/disciplines/dis-{discipline:slug}/categories', [CategoryDisciplineController::class, 'edit'])->name('admin.disciplines.categories.edit');
        Route::get('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/criteres', [CategoryDisciplineCritereController::class, 'edit'])->name('admin.disciplines.categories.criteres.edit');

        Route::get('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs', [LienDisCatTariftypeController::class, 'index'])->name('admin.disciplines.categories.tarifs.index');
        Route::get('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/edit', [LienDisCatTariftypeController::class, 'edit'])->name('admin.disciplines.categories.tarifs.edit');

        Route::get('/criteres', [CritereController::class, 'index'])->name('admin.criteres.index');
        Route::post('/criteres', [CritereController::class, 'store'])->name('admin.criteres.store');
        Route::patch('/criteres/{critere}', [CritereController::class, 'update'])->name('admin.criteres.update');
        Route::delete('/criteres/{critere}', [CritereController::class, 'destroy'])->name('admin.criteres.destroy');

        Route::post('/tarifs', [AdminTarifTypeController::class, 'store'])->name('admin.tarifs.store');
        Route::patch('/tarifs/{tarif}', [AdminTarifTypeController::class, 'update'])->name('admin.tarifs.update');
        Route::delete('/tarifs/{tarif}', [AdminTarifTypeController::class, 'destroy'])->name('admin.tarifs.destroy');

        Route::post('/tarifs/{tarif}/attributs', [AdminTarifTypeAttributController::class, 'store'])->name('admin.tarifs.attributs.store');
        Route::patch('/tarifs/{tarif}/attributs/{attribut}', [AdminTarifTypeAttributController::class, 'update'])->name('admin.tarifs.attributs.update');
        Route::delete('/tarifs/{tarif}/attributs/{attribut}', [AdminTarifTypeAttributController::class, 'destroy'])->name('admin.tarifs.attributs.destroy');

        Route::post('/discipline-similaire/{discipline}', [DisciplineSimilaireController::class, 'store'])->name('discipline-similaire.store');
        Route::put('/discipline-similaire/{discipline}', [DisciplineSimilaireController::class, 'detach'])->name('discipline-similaire.detach');

        Route::post('/disciplines', [DisciplineController::class, 'create'])->name('disciplines.create');
        Route::put('/disciplines/{discipline:slug}', [DisciplineController::class, 'update'])->name('disciplines.update');

        Route::post('/familles-disciplines/{discipline}', [FamilleDisciplineController::class, 'store'])->name('familles-disciplines.store');
        Route::put('/familles-disciplines/{discipline}', [FamilleDisciplineController::class, 'detach'])->name('familles-disciplines.detach');

        Route::post('/categories-disciplines/{discipline}', [CategoryDisciplineController::class, 'store'])->name('categories-disciplines.store');
        Route::put('/categories-disciplines/{discipline}', [CategoryDisciplineController::class, 'detach'])->name('categories-disciplines.detach');
        Route::patch('/categories-disciplines/{discipline}', [CategoryDisciplineController::class, 'update'])->name('categories-disciplines.update');

        //dupliquer les categories d'une discipline
        Route::post('/disciplines/duplicate_categories', [AdminCategorieDisciplineController::class, 'duplicate'])->name('admin.disciplines.duplicate_categories');
        //dupliquer tous les parametres categories, criteres, tarifs etc d'une discipline
        Route::post('/disciplines/duplicate_all_parameters', [AdminCategorieDisciplineController::class, 'duplicateAllParameters'])->name('admin.disciplines.duplicate_all_parameters');
        // Dupliquer que les cats et criteres d'une discipline
        Route::post('/disciplines/duplicate_categories_and_criteres', [CategoryDisciplineCritereController::class, 'duplicate'])->name('admin.disciplines.duplicate_categories_and_criteres');
        // ou 1 seule catégorie
        Route::post('/disciplines/duplicate_criteres_of_categorie', [CategoryDisciplineCritereController::class, 'duplicateOneCategorie'])->name('admin.disciplines.duplicate_criteres_of_categorie');

        //dupliquer les tarifs des categories d'une discipline
        Route::post('/disciplines/duplicate_categories_and_tarifs', [LienDisCatTariftypeController::class, 'duplicate'])->name('admin.disciplines.duplicate_categories_and_tarifs');
        // ou d'1 seule catégorie
        Route::post('/disciplines/duplicate_tarifs_of_categorie', [LienDisCatTariftypeController::class, 'duplicateOneCategorie'])->name('admin.disciplines.duplicate_tarifs_of_categorie');

        // duplicate booking fields of a DisCat
        Route::post('/disciplines/duplicate_tarif_booking_fields_of_categorie', [LienDisCatTarBookingFieldController::class, 'duplicate'])->name('admin.disciplines.duplicate_tarif_booking_fields_of_categorie');

        Route::post('/categories-disciplines-criteres', [CategoryDisciplineCritereController::class, 'store'])->name('categories-disciplines-criteres.store');
        Route::patch('/categories-disciplines-criteres/{critere}', [CategoryDisciplineCritereController::class, 'update'])->name('categories-disciplines-criteres.update');
        Route::patch('/categories-disciplines-criteres-nom/{critere}', [CategoryDisciplineCritereController::class, 'updatename'])->name('categories-disciplines-criteres-nom.updatename');
        Route::delete('/categories-disciplines-criteres/{lienDisciplineCategorieCritere}', [CategoryDisciplineCritereController::class, 'destroy'])->name('categories-disciplines-criteres.destroy');

        Route::post('/categories-disciplines-criteres-valeurs/{critere}', [CategoryDisciplineCritereValeurController::class, 'store'])->name('categories-disciplines-criteres-valeurs.store');
        Route::patch('/categories-disciplines-criteres-valeurs/{lienDisCatCritValeur}', [CategoryDisciplineCritereValeurController::class, 'update'])->name('categories-disciplines-criteres-valeurs.update');
        Route::delete('/categories-disciplines-criteres-valeurs/{lienDisCatCritValeur}', [CategoryDisciplineCritereValeurController::class, 'destroy'])->name('categories-disciplines-criteres-valeurs.destroy');

        //Sous critere
        Route::post('/dcc-sous-criteres/{valeur}', [LienDisCatCritValSsCritController::class, 'store'])->name('dcc-sous-criteres.store');
        Route::delete('/dcc-sous-criteres/{souscritere}', [LienDisCatCritValSsCritController::class, 'destroy'])->name('dcc-sous-criteres.destroy');

        // Sous critere Valeurs
        Route::post('/dcc-sous-criteres-valeurs/{souscritere}', [LienDisCatCritValSsCritValeurController::class, 'store'])->name('dcc-sous-criteres-valeurs.store');
        Route::patch('/dcc-sous-criteres-valeurs/{sousvaleur}', [LienDisCatCritValSsCritValeurController::class, 'update'])->name('dcc-sous-criteres-valeurs.update');
        Route::delete('/dcc-sous-criteres-valeurs/{sousvaleur}', [LienDisCatCritValSsCritValeurController::class, 'destroy'])->name('dcc-sous-criteres-valeurs.destroy');

        //Tarifs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs', [LienDisCatTariftypeController::class, 'store'])->name('admin.disciplines.categories.tarifs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}', [LienDisCatTariftypeController::class, 'update'])->name('admin.disciplines.categories.tarifs.update');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/toggle_show_planning', [LienDisCatTariftypeController::class, 'update_show_planning'])->name('admin.disciplines.categories.tarifs.update_show_planning');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}', [LienDisCatTariftypeController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.destroy');
        // Tarifs attributs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs', [LienDisCatTariftypeAttributController::class, 'store'])->name('admin.disciplines.categories.tarifs.attributs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}', [LienDisCatTariftypeAttributController::class, 'update'])->name('admin.disciplines.categories.tarifs.attributs.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}', [LienDisCatTariftypeAttributController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.attributs.destroy');

        // attribut - valeurs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/valeurs', [LienDisCatTarAttrValeurController::class, 'store'])->name('admin.disciplines.categories.tarifs.attributs.valeurs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/valeurs/{valeur}', [LienDisCatTarAttrValeurController::class, 'update'])->name('admin.disciplines.categories.tarifs.attributs.valeurs.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/valeurs/{valeur}', [LienDisCatTarAttrValeurController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.attributs.valeurs.destroy');

        //Tarifs attributs - sous attributs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/sous_attributs', [LienDisCatTarAttrSousAttrController::class, 'store'])->name('admin.disciplines.categories.tarifs.attributs.sous_attributs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/sous_attributs/{sousAttribut}', [LienDisCatTarAttrSousAttrController::class, 'update'])->name('admin.disciplines.categories.tarifs.attributs.sous_attributs.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/sous_attributs/{sousAttribut}', [LienDisCatTarAttrSousAttrController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.attributs.sous_attributs.destroy');

        //Tarifs attributs - sous attributs - valeurs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/sous_attributs/{sousAttribut}/valeurs', [LienDisCatTarAttrSousAttrValeurController::class, 'store'])->name('admin.disciplines.categories.tarifs.attributs.sous_attributs.valeurs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/sous_attributs/{sousAttribut}/valeurs/{valeur}', [LienDisCatTarAttrSousAttrValeurController::class, 'update'])->name('admin.disciplines.categories.tarifs.attributs.sous_attributs.valeurs.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/attributs/{attribut}/sous_attributs/{sousAttribut}/valeurs/{valeur}', [LienDisCatTarAttrSousAttrValeurController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.attributs.sous_attributs.valeurs.destroy');

        // Tarifs Booking fields
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields', [LienDisCatTarBookingFieldController::class, 'store'])->name('admin.disciplines.categories.tarifs.bookingfields.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}', [LienDisCatTarBookingFieldController::class, 'update'])->name('admin.disciplines.categories.tarifs.bookingfields.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}', [LienDisCatTarBookingFieldController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.bookingfields.destroy');

        // Tarifs Booking fields valeurs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/valeurs', [LienDisCatTarBookingFieldValeurController::class, 'store'])->name('admin.disciplines.categories.tarifs.bookingfields.valeurs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/valeurs/{valeur}', [LienDisCatTarBookingFieldValeurController::class, 'update'])->name('admin.disciplines.categories.tarifs.bookingfields.valeurs.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/valeurs/{valeur}', [LienDisCatTarBookingFieldValeurController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.bookingfields.valeurs.destroy');

        // Tarifs Booking fields sous fields
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/sous_fields', [LienDisCatTarBookingFieldSousFieldController::class, 'store'])->name('admin.disciplines.categories.tarifs.bookingfields.sous_fields.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/sous_fields/{sousField}', [LienDisCatTarBookingFieldSousFieldController::class, 'update'])->name('admin.disciplines.categories.tarifs.bookingfields.sous_fields.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/sous_fields/{sousField}', [LienDisCatTarBookingFieldSousFieldController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.bookingfields.sous_fields.destroy');

        // Tarifs Booking fields sous fields valeurs
        Route::post('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/sous_fields/{sousField}/valeurs', [LienDisCatTarBookingFieldSsFieldValeurController::class, 'store'])->name('admin.disciplines.categories.tarifs.bookingfields.sous_fields.valeurs.store');
        Route::patch('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/sous_fields/{sousField}/valeurs/{valeur}', [LienDisCatTarBookingFieldSsFieldValeurController::class, 'update'])->name('admin.disciplines.categories.tarifs.bookingfields.sous_fields.valeurs.update');
        Route::delete('/disciplines/dis-{discipline:slug}/categories/cat-{categorie}/tarifs/{tarifType}/bookingfields/{bookingfield}/sous_fields/{sousField}/valeurs/{valeur}', [LienDisCatTarBookingFieldSsFieldValeurController::class, 'destroy'])->name('admin.disciplines.categories.tarifs.bookingfields.sous_fields.valeurs.destroy');

    });
});

//cities route
require __DIR__ . '/city.php';
