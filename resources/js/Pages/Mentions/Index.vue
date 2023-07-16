<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon } from "@heroicons/vue/24/solid";
</script>

<template>
    <Head title="Mentions Légales" description="Mentions Légales" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Mentions Légales
            </h2>
        </template>

        <div class="py-4">
            <div class="mx-auto max-w-7xl px-2 md:px-4">
                <div class="flex flex-col justify-between md:flex-row">
                    <section class="flex-1 px-2 py-4 pr-0 md:mr-8">
                        <div id="conditions" class="mb-6 tracking-normal">
                            <Disclosure v-slot="{ open }">
                                <DisclosureButton
                                    class="hover:bg-bg-blue-200 flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Conditions d'utilisation</span>
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Merci de consulter le site
                                    Sports-et-loisirs.fr. Le présent document
                                    énonce les conditions d'utilisation de ce
                                    site. Tout utilisateur de ce site est réputé
                                    avoir lu, accepté et reconnu la validité des
                                    conditions ci-après énoncées, et s'engage de
                                    ce fait à les respecter et à être lié par
                                    elles. Sports-et-loisirs.fr peut, à tout
                                    moment et sans préavis, modifier ces
                                    conditions. La date de la plus récente
                                    modification apportée à ces conditions
                                    figure au bas du présent document.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Ces conditions forment l'ensemble des droits
                                    et obligations liant Sports-et-loisirs.fr et
                                    tout utilisateur de ce site, et remplacent
                                    toute condition ou disposition antérieure
                                    portant sur l'utilisation de ce site. Aucune
                                    déclaration verbale ou écrite de toute
                                    personne, ne peut avoir pour effet de
                                    modifier ou de se substituer aux présentes
                                    conditions.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div id="mentions" class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Mentions Légales</span>
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Ne sont pas acceptés dans l' annuaire
                                    Sports-et-loisirs.fr les sites contraire à
                                    l'ordre public et aux bonnes moeurs, les
                                    sites à caractère sexuel ou réservés à un
                                    publique adulte, à caractère pédophile,
                                    injurieux, diffamatoire, raciste, xénophobe,
                                    homophobe, révisionniste ou portant atteinte
                                    à l'honneur ou la réputation d'autrui,
                                    incitant à la discrimination, à la haine
                                    d'une personne ou d'un groupe de personnes à
                                    raison de leur origine ou de leur
                                    appartenance ou de leur non-appartenance à
                                    une ethnie, une nation, une race ou une
                                    religion déterminée, menaçant une personne
                                    ou un groupe de personnes, incitant au
                                    suicide, à commettre un délit, un crime ou
                                    un acte de terrorisme ou faisant l'apologie
                                    des crimes de guerre ou des crimes contre
                                    l'humanité, permettant à des tiers de se
                                    procurer directement ou indirectement des
                                    logiciels, films ou enregistrements musicaux
                                    piratés, des numéros de série de logiciels,
                                    des logiciels permettant les actes de
                                    piratage et d'intrusion dans les systèmes
                                    informatiques et de télécommunication, des
                                    virus et autres bombes logiques et d'une
                                    manière générale tout outil logiciel ou
                                    autre permettant de porter atteinte aux
                                    droits d'autrui et à la sécurité des
                                    personnes et des biens.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Dans un autre ordre, sports-et-loisirs.fr
                                    n'étant pas un annuaire généraliste, les
                                    sites qui ne sont pas des sites de clubs,
                                    cours, coachs, professeurs, lieux de sports
                                    et de loisirs, ou de sites de fédération, ne
                                    seront pas accepté dans l'annuaire, à
                                    l'exception de sites partenaires.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div id="droitauteur" class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Droit d'auteur</span>
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sports-et-loisirs.fr est propriétaire de la
                                    totalité du contenu de ce site Web, qu'il
                                    soit sous forme écrite, visuelle, binaire,
                                    sonore ou autre. Ce contenu est protégé par
                                    la législation Française et internationale
                                    relative au droit d'auteur.
                                    Sports-et-loisirs.fr détient des droits
                                    d'auteur, entre autres, sur la sélection, la
                                    coordination, l'agencement et l'amélioration
                                    de ce contenu. Il est illégal de reproduire,
                                    modifier, télécharger, publier, copier,
                                    afficher, exposer, partager ou autrement
                                    distribuer tout ou partie de ce contenu, de
                                    même que l'exploiter, notamment en
                                    participant à sa vente ou son transfert ou
                                    en créant des œuvres qui en découlent
                                    exceptés les contenus issus d'enceclopedie
                                    libre comme Wikipédia, sous condition du
                                    resepect des regles située a chaque bas de
                                    page des articles utilisés.
                                    Sports-et-loisirs.fr permet toutefois à tout
                                    utilisateur de ce site de prendre copie du
                                    contenu de celui-ci, sous réserve de la
                                    prohibition relative à l'emploi des marques
                                    officielles et d'autres marques, sans
                                    autorisation spécifique ni frais, à
                                    condition que : cette copie résulte d'un
                                    téléchargement unique sur ordinateur unique
                                    ; cette copie soit effectuée à des fins
                                    strictement personnelles et nullement
                                    commerciales ou lucratives ;
                                    Sports-et-loisirs.fr soit clairement
                                    identifiée comme la source des documents
                                    copiés avec un lien en dur; la copie ne soit
                                    pas présentée comme une version officielle
                                    des documents copiés, ni comme une copie
                                    faite en collaboration avec
                                    Sports-et-loisirs.fr, sous sa supervision ou
                                    avec son consentement ; suite à une telle
                                    copie, le contenu des documents copiés ne
                                    soit pas modifié avec pour but ou pour
                                    résultat de porter atteinte aux droits
                                    moraux dont jouit Sports-et-loisirs.fr.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sports-et-loisirs.fr ne concède aucune
                                    licence autre que celle énoncée ci-haut, et
                                    réserve par ailleurs tous ses droits de
                                    propriété intellectuelle sur le contenu du
                                    présent site.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    L'utilisateur de ce site consent à ce que
                                    Sports-et-loisirs.fr utilise ou exploite
                                    librement les idées, les concepts, le
                                    savoir-faire ou les techniques qui lui sont
                                    communiqués par voie électronique, quelle
                                    qu'en soit la fin, et cède à
                                    Sports-et-loisirs.fr ses droits éventuels à
                                    cet égard, sans que Sports-et-loisirs.fr ne
                                    soit tenu au versement de quelque redevance
                                    ou prestation que ce soit, mais sous réserve
                                    de l'engagement de Sports-et-loisirs.fr en
                                    matière de protection des renseignements
                                    personnels. Le webmaster qui propose à
                                    Sports-et-loisirs.fr de référencer son site,
                                    ne peut le supprimer seul sans l'accord de
                                    Sports-et-loisirs.fr, ceci pour des raisons
                                    d'exactitude sur les moteurs de recherches.
                                    Sports-et-loisirs.fr se réserve le droit de
                                    refuser la suppression d'une url référencée,
                                    d'une description ou d'un titre de site.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div
                            id="integriteEtExactitude"
                            class="mb-6 tracking-normal"
                        >
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Intégrité et exactitude de
                                        l'information</span
                                    >
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sports-et-loisirs.fr s'efforce de faire en
                                    sorte que l'information disponible dans ce
                                    site soit complète, exacte et actualisée.
                                    Cette information provient de sources que
                                    Sports-et-loisirs.fr juge fiables, et est
                                    régulièrement mise à jour. Elle peut
                                    toutefois comporter des inexactitudes, des
                                    omissions, des fautes typographiques ou
                                    d'autres lacunes.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    En conséquence, ce site Web et l'intégralité
                                    de son contenu sont mis à la disposition du
                                    public tel quel, sans aucune garantie
                                    d'aucune nature, explicite ou implicite, y
                                    compris, entre autres, les garanties
                                    implicites de qualité marchande, d'absence
                                    de contrefaçon ou d'adéquation à des besoins
                                    ou usages particuliers. Sans limiter la
                                    généralité de ce qui précède,
                                    Sports-et-loisirs.fr n'offre aucune garantie
                                    quant à la disponibilité ou la stabilité du
                                    contenu de ce site, quant à la possibilité
                                    d'y accéder, ou quant à la précision, la
                                    fiabilité ou l'exactitude de l'information
                                    offerte. L'utilisateur est prié de prendre
                                    note que ce site fait régulièrement l'objet
                                    d'opérations d'entretien technique,
                                    lesquelles peuvent en limiter ou en empêcher
                                    l'accès.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sports-et-loisirs.fr ne peut être tenu
                                    responsable de quelque dommage que ce soit,
                                    direct ou indirect, subi en relation avec
                                    l'utilisation de ce site Web, incluant
                                    notamment les préjudices particuliers, les
                                    dommages punitifs, exemplaires, accessoires,
                                    moraux ou consécutifs, la perte de
                                    bénéfices, le manque à gagner ou la perte
                                    d'information, les dommages pour perte
                                    d'exploitation, perte ou effacement de
                                    programmes ou de données dans un système
                                    d'information, et les autres dépenses ou
                                    dommages qui résulteraient de l'utilisation
                                    ou de la non-disponibilité de ce site Web,
                                    d'une interruption, d'une défectuosité, d'un
                                    délai de traitement ou de transmission, ou
                                    de la présence d'un virus, d'un ver ou d'un
                                    cheval de Troie informatique, ou d'autres
                                    éléments destructeurs ou d'une panne de
                                    courant ou de système, et ce, que
                                    Sports-et-loisirs.fr soit informé ou non de
                                    la possibilité de ces dommages, pertes ou
                                    dépenses.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Tout utilisateur du présent site Web le fait
                                    à ses propres risques et s'engage à
                                    indemniser et tenir à couvert
                                    Sports-et-loisirs.fr, de même que ses
                                    dirigeants, administrateurs, employés,
                                    fournisseurs, entrepreneurs ou mandataires,
                                    de toute responsabilité civile, criminelle
                                    ou quasi-criminelle liée à l'utilisation de
                                    ce site, sans égard à la négligence de ces
                                    derniers.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div
                            id="modificationsEtContenuIllegaux"
                            class="mb-6 tracking-normal"
                        >
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Modifications et contenu illégaux</span
                                    >
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sports-et-loisirs.fr n'autorise pas les
                                    utilisateurs de ce site Web à en modifier,
                                    reformuler, adapter, changer, ajuster,
                                    réarranger, démonter, délimiter ou décoder
                                    le contenu, sauf dans la mesure des
                                    dispositifs de personnalisation de
                                    l'information spécifiquement mis à la
                                    disposition des utilisateurs par
                                    Sports-et-loisirs.fr.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    De plus, il est strictement interdit
                                    d'afficher dans ce site Web, ou de
                                    transmettre par l'intermédiaire de celui-ci,
                                    du matériel illégal, des menaces, de même
                                    que des textes, des sons ou des images de
                                    nature diffamatoire, obscène, frauduleuse,
                                    scandaleuse, séditieuse, pornographique ou
                                    blasphématoire, ou tout autre matériel
                                    susceptible de constituer, favoriser ou
                                    encourager la commission d'une infraction
                                    criminelle ou pénale, donner lieu à une
                                    poursuite en responsabilité civile ou violer
                                    de toute autre façon les lois en vigueur.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div id="hyperliens" class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Hyperliens vers d'autres sites</span>
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Ce site contient des hyperliens, lesquels
                                    donnent rapidement accès à des sites
                                    Internet appartenant à des tiers, et gérés
                                    par ces derniers. Sports-et-loisirs.fr met
                                    ces hyperliens à la disposition des usagers
                                    de son propre site à seule fin de commodité,
                                    et n'exerce aucune forme de contrôle sur le
                                    contenu des sites auxquels mènent ces
                                    hyperliens. En outre, Sports-et-loisirs.fr
                                    ne fait aucune déclaration, n'émet aucune
                                    approbation et n'accepte aucune
                                    responsabilité à l'égard de la teneur ou de
                                    l'utilisation de tels sites. Certains de ces
                                    sites peuvent placer des témoins permanents
                                    ou temporaires, communément appelés cookies,
                                    dans les disques durs des ordinateurs de
                                    leurs utilisateurs.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div
                            id="sauvegardeViePrivee"
                            class="mb-6 tracking-normal"
                        >
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Sauvegarde de la vie privée</span>
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Aucun utilisateur de ce site n'est tenu de
                                    fournir quelque renseignement personnel que
                                    ce soit afin de pouvoir accéder au contenu
                                    du site. Toutefois, en certaines
                                    circonstances, Sports-et-loisirs.fr offre
                                    aux utilisateurs la possibilité de lui
                                    fournir des informations, afin d'être en
                                    mesure de leur fournir certains services ou
                                    avantages spécifiques, comme la soumission
                                    d'un site dans l'annuaire, la réception
                                    d'actualités, l'inscription à la zone de
                                    rencontres, l'inscription sur la liste des
                                    pratiquants en attente.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Les renseignements personnels créés,
                                    recueillis ou conservés par
                                    Sports-et-loisirs.fr sont assujetties à la
                                    Commission Nationale de l'Informatique et
                                    des Libertés (CNIL). Toute personne physique
                                    dispose d'un droit d'accès et, dans certains
                                    cas, de rectification à l'égard des
                                    renseignements qui la concernent que détient
                                    Sports-et-loisirs.fr.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sports-et-loisirs.fr ne vend, loue, échange,
                                    prête ou cède aucun renseignement personnel
                                    qui lui parvient par des moyens
                                    électroniques de communication, à moins que
                                    l'expéditeur de tout tel renseignement n'y
                                    consente ou que Sports-et-loisirs.fr n'y
                                    soit contrainte par la loi ou par la
                                    décision d'un tribunal compétent.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Lorsqu'un utilisateur accède à ce site, un
                                    échange de données s'effectue
                                    automatiquement entre l'ordinateur de cet
                                    utilisateur et le système informatique de
                                    Sports-et-loisirs.fr. Ces données sont : le
                                    nom de domaine Internet, l'adresse de
                                    protocole Internet, le type de navigateur et
                                    le type de système d'exploitation
                                    d'ordinateur à partir desquels l'utilisateur
                                    accède à ce site ; la date et l'heure
                                    d'accès ; les pages parcourues ; l'adresse
                                    du site Internet référent, le cas échéant.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Cet échange de données est indispensable
                                    afin que le serveur de Sports-et-loisirs.fr
                                    transmette des fichiers compatibles avec le
                                    genre d'équipement informatique employé par
                                    l'utilisateur. Ces données sont conservées
                                    dans un fichier journal et permettent de
                                    compiler des statistiques et données
                                    techniques ayant trait à l'utilisation du
                                    site Web. Toutefois, elles ne permettent pas
                                    d'identifier personnellement l'utilisateur.
                                    Sports-et-loisirs.fr ne tente pas d'établir
                                    l'identité personnelle d'un utilisateur,
                                    sauf si elle détecte une manœuvre contraire
                                    aux conditions d'utilisation de ce site.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    De façon générale, ce site Web ne comporte
                                    pas de fichiers témoins ( cookies ).
                                    Cependant, l'accès à certains services
                                    requiert qu'un fichier témoin temporaire
                                    soit placé dans l'ordinateur de
                                    l'utilisateur. L'utilisateur peut configurer
                                    son navigateur Internet de façon à empêcher
                                    l'installation de pareils fichiers dans le
                                    disque dur de son ordinateur. En ce cas, il
                                    ne pourra cependant pas accéder à certains
                                    services ou documents offerts dans ce site.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Ce site Web ne comporte aucune zone dite
                                    sécurisée, permettant d'établir un dialogue
                                    séparé et confidentiel entre l'ordinateur
                                    d'un utilisateur du site et le système
                                    informatique de Sports-et-loisirs.fr. De
                                    façon générale, aucune transmission de
                                    données au moyen du réseau Internet ne peut
                                    être entièrement à l'abri de tout
                                    détournement ou usage malicieux. En
                                    conséquence, Sports-et-loisirs.fr ne peut
                                    garantir que les renseignements qui sont
                                    expédiés à son intention par voie de
                                    courrier électronique ou d'Internet lui
                                    parviendront sans avoir été falsifiés,
                                    consultés, altérés, ou sans que leur
                                    intégrité ou leur authenticité puisse avoir
                                    été autrement mise en cause, ou même que ces
                                    renseignements parviendront à destination.
                                    L'engagement de Sports-et-loisirs.fr en
                                    matière de protection des renseignements
                                    personnels porte exclusivement sur le
                                    traitement des renseignements qui sont
                                    effectivement en sa possession. Aussi, nous
                                    faisons appel à des entreprises de publicité
                                    tierces pour la diffusion d'annonces sur
                                    notre site Web. Ces entreprises peuvent
                                    utiliser les données relatives à votre
                                    navigation sur notre site Web ou d'autres
                                    sites (à l'exception de votre nom, adresse
                                    postale, adresse e-mail ou numéro de
                                    téléphone) afin de vous proposer des
                                    annonces de produits ou services adaptées à
                                    vos centres d'intérêt. Pour en savoir plus
                                    sur cette pratique ou sur la possibilité
                                    d'interdire l'utilisation de ces données par
                                    ces entreprises, cliquez ici.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div
                            id="gestionEtDifficultes"
                            class="mb-6 tracking-normal"
                        >
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Gestion du site et difficultés
                                        techniques</span
                                    >
                                    <ChevronUpIcon
                                        :class="
                                            open ? 'rotate-180 transform' : ''
                                        "
                                        class="h-5 w-5 text-blue-500"
                                    />
                                </DisclosureButton>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Ce site est entièrement conçu et géré par
                                    Sports-et-loisirs.fr. Prière de communiquer
                                    à l'administrateur du site toute difficulté
                                    technique ou erreur rencontrée en cours de
                                    navigation.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                    </section>
                    <aside class="hidden px-2 py-4">
                        <ul
                            class="divide-y divide-gray-200 border border-gray-200 text-gray-700"
                        >
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#conditions"
                                    >Conditions d'utilisations</Link
                                >
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#mentions">Mentions Légales</Link>
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#droitauteur">Droit d'auteur</Link>
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#integriteEtExactitude"
                                    >Intégrité et exactitude de
                                    l'information</Link
                                >
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#modificationsEtContenuIllegaux"
                                    >Modifications et contenu illégaux</Link
                                >
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#hyperliens"
                                    >Hyperliens vers d'autres sites</Link
                                >
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#sauvegardeViePrivee"
                                    >Sauvegarde de la vie privée</Link
                                >
                            </li>
                            <li
                                class="cursor-pointer px-4 py-2 hover:bg-midnight hover:text-white"
                            >
                                <Link href="#gestionEtDifficultes"
                                    >Gestion du site et difficultés
                                    techniques</Link
                                >
                            </li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
