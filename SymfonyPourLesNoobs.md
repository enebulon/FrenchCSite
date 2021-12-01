## Créer un projet
```
composer create-project symfony/skeleton nomProjet
ou avec tout ce qu'il faut pour une application web :
composer create-project symfony/website-skeleton nomProjet
```
## require de base
```
composer require --dev profiler maker
composer require annotations twig form validator orm asset
``` 
## Lancement du projet
```
sudo service apache2 start
sudo service mysql start
# composer install
symfony server:start
``` 
### .env.local
```
DATABASE_URL=mysql://user:user@localhost:3306/nomBD
```
## Commande sf
```
php bin/console make:controller
php bin/console make:entity
php bin/console make:form
php bin/console make:crud
php bin/console make:migration
php bin/console doctrine:migrations:migrate
aka
bin/console d:m:m
``` 
## Controller
```php
/**
* Liste de toutes les catégories.
*
* @Route("/categorie", name="categorie")
*/
public function categorie() {
    $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
    return $this->render('default/categorie.html.twig', ['categories' => $categories]);
}

// un produit
/**
* @Route("/produit/{id}", name="Produit")
*/
public function show(int $id): Response {
    $product = $this->getDoctrine()->getRepository(Produit::class)->find($id);
    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }
    return $this->render('default/produit.html.twig', [
        'controller_name' => 'Produit',
        'produit' => $product,
    ]);
}

// formulaire
public function ajoutProduit(Request $request) {
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute('produit');
    }
    return $this->render('default/produit_ajout.html.twig', ['form' => $form->createView()]);
}
```
## Entity
type pour les relations : relation
## Assert pour les Form
https://symfony.com/doc/current/validation.html#basic-constraints
```php
// Entity
/**
* @Assert\Length(
*      min = 2,
*      max = 50,
*      minMessage = "Your first name must be at least {{ limit }} characters long",
*      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
* )
*/
protected $firstName;
```
## Vue / twig
```twig
{% extends 'base.html.twig' %}
{% block title %}{{ controller_name }}{% endblock %}
{% block body %}
{% endblock %}

{{ produit.nom  }}

{% for produit in produits %}
{% endfor %}

{{ form_start(form) }}
{{ form_widget(form) }}
<button type="submit">Ajouter</button>
{{ form_end(form) }}

href="{{ path('homepage') }}"
src="{{ asset("images/#{produit.image}")  }}"

{# ... #}
```
## Fixtures
```
composer require --dev orm-fixtures
php bin/console make:fixtures
php bin/console doctrine:fixtures:load
```
```php
// création 20 joueurs! Bam!
for ($i = 0; $i < 20; $i++) {
  $joueur = new Joueur();
  $joueur->setNom('joueur '.$i);
  $joueur->setNbButs(mt_rand(1, 10));
  $manager->persist($joueur);
}
$userA = new Utilisateur();
$userA->setUsername('admin');
$userA->setPassword($this->passwordEncoder->encodePassword($userA, 'admin'));
$userA->setRoles(array('ROLE_ADMIN'));
$manager->persist($userA);

$manager->flush();

```
## Repository
```php
public function nbCategories() {
    return $this->createQueryBuilder('c')
        ->select('COUNT(c)')
        ->getQuery()
        ->getSingleScalarResult()
        ;
}

public function catEmpty() {
    return $this->createQueryBuilder('c')
        ->andWhere('NOT EXISTS (SELECT 1 FROM App\Entity\Produit p WHERE c.id = p.categorie)')
        ->getQuery()
        ->getResult()
        ;
}

function findProduitsByTag($tag){
    return $this->createQueryBuilder('p')
        ->select('p')
        ->innerJoin('p.tags','t')
        ->where('t.nom=:tag')
        ->orderBy('p.nom','ASC')
        ->setParameter('tag',$tag)
        ->getQuery()
        ->getResult();
}
```
## Sécurité
```
composer req security
# on fabrique l'entité utilisateur
php bin/console make:user
# on modifie l'entité en lui ajoutant les propriétés supplémentaire voulues (tél, adresse, avatar, ...)
php bin/console make:entity Entité_Utilisateur
# on fabrique la gestion de l'authentification à travers un contrôleur spécifique
php bin/console make:auth
# il ne reste plus qu'à enregistrer un ou plusieurs utilisateurs dans la base
php bin/console security:encode-password
# et de modifier l'access_control pour tester
```
```yaml
# security.yaml
access_control:
    - { path: ^/admin, roles: ROLE_ADMIN }
    - { path: ^/produit/ajout, roles: ROLE_USER }
```
```php
/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
```
```twig
{% if is_granted('ROLE_ADMIN') %}
  <a href="...">Supprimer</a>
{% endif %}

{% if is_granted('IS_AUTHENTICATED_FULLY') %}
  <p>Bonjour {{ app.user.username }}</p>
{% endif %}
```
## EasyAdmin
```
composer require admin
bin/console make:admin:dashboard
bin/console make:admin:crud
```
```php
// DashboardController.php
public function index(): Response {
    //return parent::index();
    $routeBuilder = $this->get(AdminUrlGenerator::class);
    return $this->redirect($routeBuilder->setController(CategorieCrudController::class)->generateUrl());
}

public function configureMenuItems(): iterable {
    yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
    yield MenuItem::linkToCrud('Categorie', 'fas fa-list', Categorie::class);
    yield MenuItem::linkToCrud('Produit', 'fas fa-list', Produit::class);
    yield MenuItem::linkToCrud('Tag', 'fas fa-list', Tag::class);
}

// ProduitCrudController.php
public function configureFields(string $pageName): iterable {
    return [
        TextField::new('nom'),
        TextareaField::new('description'),
        NumberField::new('prix'),
        TextField::new('image'),
        AssociationField::new('categorie'),
        AssociationField::new('tags'),
    ];
}
// pour les associations il faut un toString à la class
```
```yaml
# config/packages/translation.yaml
default_locale: fr
```
## Gestion des fichier
```
composer require vich/uploader-bundle
```
https://github.com/dustin10/VichUploaderBundle/blob/master/docs/usage.md
```yaml
# config/packages/vich_uploader.yaml
vich_uploader:
    db_driver: orm

    mappings:
        image:
            uri_prefix: /images
            upload_destination: '%kernel.project_dir%/public/images'
            # namer: Vich\UploaderBundle\Naming\SmartUniqueNamer
```
```php
// Entity
// Image.php
/**
 * NOTE: This is not a mapped field of entity metadata, just a simple property.
 *
 * @Vich\UploadableField(mapping="image", fileNameProperty="URL")
 *
 * @var File|null
 */
private $imageFile;

/**
 * @ORM\Column(type="string")
 *
 * @var string|null
 */
private $URL;

/**
 * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
 * of 'UploadedFile' is injected into this setter to trigger the update. If this
 * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
 * must be able to accept an instance of 'File' as the bundle will inject one here
 * during Doctrine hydration.
 *
 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
 */
public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;
}
```
```php
// Formulaire
// ImageType.php
public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
        ->add('nom')
        ->add('description')
        ->add('visible')
        ->add('imageFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => true,
            'delete_label' => '...',
            'download_label' => '...',
            'download_uri' => true,
            'image_uri' => true,
            'imagine_pattern' => '...',
            'asset_helper' => true,
        ])
    ;
}
```

## mail
```
composer require mail
```
```php
// controller : bin/console make:controller DefaultController
public function index(Request $request, MailerInterface $mailer) {
    $form = $this->createForm(ContactType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) :
        $this->sendEmail($form->getData(),$mailer)
        return $this->redirect($request->getUri());
    endif;
    return $this->render('default/index.html.twig', ['form' => $form->createView()]);
}
private function sendEmail($data, MailerInterface $mailer) {
    $email = new Email();
    $email->from($data["email"])
        ->to('nicolas.trugeon@univ-lr.fr')
        ->subject($data["nom"])
        ->text($data["message"]);
    $mailer->send($email);
}
// form : bin/console make:form ContactType
public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
        ->add('nom', TextType::class, array('label'=>false, 'attr' => array('placeholder' => 'Votre nom'), 'constraints' => array(new NotBlank(array("message" => "Merci de renseigner votre nom.")))))
        ->add('email', EmailType::class, array('label'=>false,'attr'=> array('placeholder'=>'Votre email')))
        ->add('message',TextareaType::class,array('label'=>false,'attr'=> array('placeholder'=>'Votre message')));
}
// .env
MAILER_URL=null://localhost
```