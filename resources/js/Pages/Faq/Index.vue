<script setup>
import ResultLayout from "@/Layouts/ResultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon } from "@heroicons/vue/24/solid";
import { HomeIcon, QuestionMarkCircleIcon } from "@heroicons/vue/24/outline";
import FamilleResultNavigation from "@/Components/Familles/FamilleResultNavigation.vue";
import ResultsHeader from "@/Components/ResultsHeader.vue";
const props = defineProps({
    familles: Object,
    listDisciplines: Object,
    allCities: Object,
});
</script>

<template>
    <Head title="Questions fréquentes" description="Questions fréquentes" />

    <ResultLayout :listDisciplines="listDisciplines" :allCities="allCities">
        <template #header>
            <FamilleResultNavigation :familles="familles" />
            <ResultsHeader>
                <template v-slot:title>
                    <h1
                        class="border-b-2 border-slate-400 pb-2 text-2xl font-black leading-tight tracking-widest text-gray-600 md:text-4xl"
                    >
                        <QuestionMarkCircleIcon
                            class="mr-2 inline-block h-6 w-6 text-gray-600"
                        ></QuestionMarkCircleIcon
                        >Questions fréquentes
                    </h1>
                </template>
                <template v-slot:ariane>
                    <nav aria-label="Breadcrumb" class="flex">
                        <ol
                            class="flex overflow-hidden rounded-lg border border-gray-200 text-gray-600"
                        >
                            <li class="flex items-center">
                                <Link
                                    preserve-scroll
                                    :href="route('welcome')"
                                    class="flex h-10 items-center gap-1.5 bg-gray-100 px-4 transition hover:text-gray-900"
                                >
                                    <HomeIcon class="h-4 w-4" />

                                    <span
                                        class="ms-1.5 hidden text-xs font-medium md:block"
                                    >
                                        Accueil
                                    </span>
                                </Link>
                            </li>

                            <li class="relative flex items-center">
                                <span
                                    class="absolute inset-y-0 -start-px h-10 w-4 bg-gray-100 [clip-path:_polygon(0_0,_0%_100%,_100%_50%)] rtl:rotate-180"
                                >
                                </span>

                                <Link
                                    preserve-scroll
                                    :href="route('faq.index')"
                                    class="flex h-10 shrink-0 items-center bg-white pe-4 ps-8 text-xs font-medium transition hover:text-gray-900"
                                >
                                    faq
                                </Link>
                            </li>
                        </ol>
                    </nav>
                </template>
            </ResultsHeader>
        </template>

        <div class="py-4">
            <div class="mx-auto max-w-full px-2 md:px-4">
                <div class="flex flex-col justify-between md:flex-row">
                    <section class="flex-1 px-2 py-4 pr-0 md:mr-8">
                        <div class="mb-6 tracking-normal">
                            <Disclosure v-slot="{ open }">
                                <DisclosureButton
                                    class="hover:bg-bg-blue-200 flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Comment chercher ?</span>
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
                                    Pour cherche un club, un cours, un lieux, ou
                                    un professeur, il vous suffit d'utiliser le
                                    menu de navigation en haut de la page clubs.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Préciser le type de discipline que vous
                                    souhaitez pratiquer, ou bien votre localité.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    La liste des structure inscrites
                                    s'afficheront alors, puis la liste des
                                    structures répondant à des disciplines
                                    proches et lieux de pratiques à proximité.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Je cherche, mais je ne trouve
                                        pas...</span
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
                                    L'ensemble des structures qui apparaissent
                                    sur ce site se sont pour la plupart
                                    inscrites par elles-mêmes. D'autres ont
                                    préférée ne pas figurer dans notre base de
                                    données. Nous n'avons donc pas toutes les
                                    coordonnées, il se peut qu'il n'y ai donc
                                    pas de structure répondant à votre demande
                                    dans votre zone.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Mon club n'est pas référencé dans
                                        l'annuaire... est ce normal ?</span
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
                                    Si vous n'êtes pas encore dans l'annuaire
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    - Vous ne vous êtes pas inscrit, dans ce
                                    cas, vous pouvez vous
                                    <Link :href="route('register')"
                                        >inscrire ici
                                    </Link>
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    - Vous vous êtes inscrit, mais vous n'avez
                                    pas validé votre inscription. Pour cela, il
                                    suffit de cliquer sur le lien reçu dans
                                    l'email utilisé lors de votre inscription.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Comment inscrire un club ?</span>
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
                                    Pour inscrire un club, il vous faut d'abord
                                    ouvrir un compte utilisateur,
                                    <Link :href="route('register')"
                                        >en cliquant ici
                                    </Link>
                                    .
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Vous recevrez un mail avec un lien
                                    permettant de valider votre inscription.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Une fois votre inscription validée, vous
                                    pourrez vous connecter sur votre espace
                                    client et y ajouter les informations de
                                    votre club, comme ses coordonnées, les lieux
                                    d'entrainement, les horaires de pratique,
                                    les tarifs, une présentation détaillée...
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Vous pourrez également annoncer vos
                                    événements, comme des stages,
                                    représentations, portes ouvertes...
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >L'inscription est elle gratuite?</span
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
                                    Oui l'inscription est entièrement gratuite.
                                    Notre rémunération est issue des revenus
                                    publicitaires.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Qu'est ce que mon inscription peut
                                        m'apporter?</span
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
                                    La publication de la fiche de votre
                                    structure sur notre site vous assure une
                                    visibilité sur le web. Très bien positionnés
                                    sur de nombreux mots clefs, les internautes
                                    intéréssés pourront vous trouver facilement,
                                    et avoir toutes les informations qu'ils
                                    recherchent pour faciliter leur choix.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Aussi, un lien vers votre site web permettra
                                    d'augmenter votre référencement, et de mieux
                                    vous positionner sur les mots clefs relatifs
                                    à votre discipline dans votre secteur.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    En bref, avoir une fiche sur notre site
                                    augmente votre nombre d'édhérents
                                    potentiels.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Je n'arrive pas à me connecter sur mon
                                        espace...</span
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
                                    Si vous n'arrivez pas à vous connecter sur
                                    votre espace, plusieurs raisons possibles:
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    - Vous vous êtes trompé d'email ou de mot de
                                    passe. Vous pouvez
                                    <Link :href="route('password.request')"
                                        >réinitialiser votre mot de passe ici </Link
                                    >.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    - Vous vous êtes inscrit, mais vous n'avez
                                    pas validé votre inscription. Pour cela, il
                                    suffit de cliquer sur le lien reçu dans
                                    l'email utilisé lors de votre inscription.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    - Vous avez changé votre email de connection
                                    via votre espace. Vous avez été déconnecté,
                                    et un nouvel email de confirmation vous a
                                    été renvoyé sur votre nouvelle adresse. Il
                                    faut donc cliquer sur le lien reçu dans
                                    votre nouvel email de connection pour
                                    valider votre inscription.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    - Votre navigateur n'accepte pas les
                                    cookies. Il faut les accepter, car les
                                    cookies participent au bon fonctionnement de
                                    notre site, et du compte client
                                    principalement.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Comment mettre à jour mes
                                        informations?</span
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
                                    Pour mettre à jour vos informations, il faut
                                    vous connecter sur votre espace. Vous
                                    pourrez alors facilement ajouter, supprimer,
                                    et modifier des informations.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sachez que plus votre fiche est actualisée,
                                    plus le classement dans nos pages sera
                                    élevé.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Je ne reçois pas les mails</span>
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
                                    Si vous ne recevez pas nos mails, c'est
                                    probablement que votre service de messagerie
                                    nous considère comme spam.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Pensez à regarder dans votre boite "courrier
                                    indésirable", et indiquez à votre messagerie
                                    que nous ne sommes pas un spam.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span>Je ne reçois pas les mails</span>
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
                                    Si vous ne recevez pas nos mails, c'est
                                    probablement que votre service de messagerie
                                    nous considère comme spam.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Pensez à regarder dans votre boite "courrier
                                    indésirable", et indiquez à votre messagerie
                                    que nous ne sommes pas un spam.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                        <div class="mb-6 tracking-normal">
                            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                                <DisclosureButton
                                    class="flex w-full justify-between rounded-lg bg-blue-100 px-4 py-2 text-left text-base font-semibold text-gray-900 hover:bg-blue-200 focus:outline-none focus-visible:ring focus-visible:ring-blue-500 focus-visible:ring-opacity-75"
                                >
                                    <span
                                        >Comment me désinscrire, supprimer mon
                                        compte?</span
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
                                    Pour supprimer la fiche de votre structure,
                                    ou pour supprimer votre compte, il faut dans
                                    un premier temps vous connecter sur votre
                                    espace.
                                </DisclosurePanel>
                                <DisclosurePanel
                                    class="px-4 pb-2 pt-4 text-base text-gray-600"
                                >
                                    Sur la page de votre club, et si vous êtes
                                    l'auteur de la fiche, un bouton vous permet
                                    de supprimer la page de votre club.
                                </DisclosurePanel>
                            </Disclosure>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </ResultLayout>
</template>
