## Créer un projet
```
vue create mon_projet
```
## require de base
```bash
vue add router
vue add vuex
vue add vuetify
# ou si votre projet existe déjà
npm install vue-router vuex axios vuetify --save
``` 
## Lancement du projet
```
npm run serve
``` 
## installation des dependences d'un projet vuejs
```
npm install
``` 
## exemple d'un components
```html
<script>
import UnComponent from './UnComponent.vue'

export default {
  // nom du composant
  name: "name",
  // variables du composant
  data() {
    return {
      variable: 'value'
    }
  },
  // fonctions du composant
  methods: {
    imageUrl(p) {
        // permet de placer les images dans le dossier assets et que cela fonctionne qu’elle que soit la route…
        return require('@/assets/' + p)
    }
  },
  // variables définies lors de l'appel du composant par le parent
  props: {
    msg: String
  },
  // permet d'associer une nouvelle variable à une fonction pour un calcul par exemple
  computed: {
    nomComplet() {
        return this.nom + ' ' + this.prenom
    }
  },
  // permet de savoir quand une variable a été modifiée et de faire une action en conséquence
  watch: {
    nom(nouveau, ancien) {
        // je peux agir en ayant en paramètre l'ancienne valeur et la nouvelle
    }
  },
  // fonction qui est appelée au lancement du composant
  mounted() {
      // fonction appelée quand le composant est chargé
  },
  components: {
    UnComponent
  }
}
</script>
```
## Lifecycle Diagram
https://vuejs.org/images/lifecycle.png

## Parent / Enfant
```html
<liste-contacts :contacts="personnes"/>
```
```js
// Enfant
add() {
      this.$emit('add', this.ville)
      this.ville = ''
    }
// Parent
<config @add="add" v-if="visible"/>
add(ville) {
      this.villes.push(ville)
      localStorage.setItem('villes', JSON.stringify(this.villes))
    }
```
### $parent : l'objet parent du composant
```this.$parent.visible``` permet de récupérer la propriété visible du parent
### $emit : gestion des évènements entre parent et enfant
```this.$emit('next')``` permet d'appeler l'évènement appelé "next" du parent
## Slot
```<slot>``` permet de récupérer le code html du composant dans ```<nom_composant> code </nom_composant>``` est très utilisé quand on utilise des bibliothèques tierces
## Routage
```hash``` par défaut -> URL du style localhost/#/chemin

```history``` -> URL du style localhost/chemin

router/index.js
```js
import Home from '../components/Home.vue'

export default new Router({
    mode: 'hash',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            // :num : variable ?: facultatif
            path: '/blog/:num?',
            name: 'blog',
            component: Blog
        },
        {
            path: '*', // chemin par défaut
            redirect: '/'
        }
    ]
})
```
template
```html
<router-link to="chemin">texte</router-link>
<router-link :to="{name: 'nom_route'}">texte</router-link>
<router-link :to="{name: 'nom_route', params: {cles: valeur} }">texte</router-link>
````
component
```js
// au niveau du script du composant, on peut accéder à une route
this.$router.push( {name : 'nom_route', params: {clés: valeurs}})
// au niveau du script du composant appelé, on peut accéder au paramètre
this.$route.params.num
```
## Vuex / store
store/index.js
```js
export default new Vuex.Store({
  state: {
      nom: 'nom',
      prenom: 'prenom',
      compteur: 0,
      tabJoueurs: []
  },
  mutations: {
    increment(state) {
      state.compteur++;
    },
    setDataJoueurs(state,data) {
      state.tabJoueurs = data['hydra:member']
    }
  },
  actions: {
    getDataApi(context) {
      // appel d'une api symfony asynchrone
      // ...
      // joueursViaApiSf est le retour de l'appel d'Api
      context.commit("setDataJoueurs", joueursViaApiSf)
    }
  },
  getters: {
    tabJoueursStadeRochelais: (state) => {
      return state.tabJoueurs.filter(j => j.equipe === 'Stade Rochelais')
    }
  }
})
```
component
```js
this.$store.state.nom
this.$store.commit(increment)
this.$store.dispatch('getDataApi')
this.$store.getters.tabJoueursStadeRochelais
```
## Limite du computed
on ne peut pas associer un élément computed avec v-model

Dans ce cas, il faut définir le getter et le setter associé
```js
computed: {
  nomComplet: { // issu de la concaténation entre nom et prenom
    get() {
      return this.nom + ' ' + this.prenom
    },
    set(valeur) {
      let tabValeur = valeur.split(' ')
      this.nom = tabValeur[0]
      this.prenom = tabValeur[1]
    }
  }
}
```
## Fonction js
### filter
À la place de v-if sur un élément
```html
<ul v-for="(c, index) in contacts" :key="index">
  <li v-if="c.favorite">{{ c.nom }}</li>
</ul>
```
on peut filtrer facilement une liste sur un critère
```js
computed: {
  contactsFavoris() {
    return this.contacts.filter(contact => contact.favorite)
  }
}
```
### map
retourne un tableau modifié à travers une fonction
```js
const tabJoueurs= [
  'Platini' ,'Maradona'
]
const tabJoueursMaj = tabJoueurs.map(j => j.toUpperCase())
```
on a deux tableaux, un avec les noms originaux, et un avec les noms en majuscules
## Axios
```js
import Axios from 'axios'

Axios.get(url)
     .then(response => {
        const donnees = response.data
        // on poursuit le traitement
      })
     .catch( e => {
        const erreurs=e
        // on poursuit la gestion des erreurs
     })

Axios.post(url, objetJson)
        .then((response) => {
          // response est l'objet qui a été ajouté dans la base de données par exemple. 
          // On peut éventuellement récupérer son id.
        })  
        .catch(error => {
          console.log(error)
        })
```
## LocalStorage
```js
// en écriture
// si this.villes est un tableau, il faut le transformer en chaine...
localStorage.setItem('villes',JSON.stringify(this.villes))
// en lecture
this.villes=JSON.parse(localStorage.getItem('villes'))
```
## Vuetify
permet d'ajouter des éléments material design à VueJS

https://vuetifyjs.com/

un composant qui veut utiliser vuetify doit utiliser la balise parente ```<v-app>```
```html
<template>
  <v-footer height="auto" dark="">
    <v-card flat="" tile="" color="#1D2939" class="text-xs-center">
      <v-card-text>
        <v-btn v-for="icon in icons" :key="icon" class="mx-3 white--text" icon="">
          <v-icon size="24px">{{ icon }}</v-icon>
        </v-btn>
      </v-card-text>
      <v-card-text class="white--text pt-0">
        Phasellus feugiat arcu sapien, et iaculis ipsum elementum sit amet. Mauris cursus 
      </v-card-text>
      <v-divider></v-divider>
      <v-card-text class="white--text">©2021 — <strong>Vuetify</strong></v-card-text>
    </v-card>
  </v-footer>
</template>
```
```js
export default {
  data() {
    return {
      icons: [
        'fab fa-facebook',
        'fab fa-twitter',
        'fab fa-google-plus',
        'fab fa-linkedin',
        'fab fa-instagram'
      ]
    }
  }
}
```
## Transition et animation
https://vuejs.org/v2/guide/transitions.html
```html
<transition name="addcontact">
  <ajouter-contact v-if="visible"></ajouter-contact>
</transition>
```
```css
.addcontact-enter-active, .addcontact-leave-active {
   transition: opacity 1s;
}
.addcontact-enter, .addcontact-leave-to  {
  opacity: 0;
}
```