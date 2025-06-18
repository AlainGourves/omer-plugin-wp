// public/js/demi-sel.js

// Attend que le DOM soit complètement chargé avant de monter l'application Vue.
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si la variable vueAppData est définie par wp_localize_script
    // et si le conteneur #demi-sel-root existe.
    if (typeof vueAppData !== 'undefined' && document.getElementById('demi-sel-root')) {
        const { createApp, ref } = Vue;

        createApp({
            setup() {
                // Initialise le message avec la valeur passée depuis WordPress
                const message = ref(vueAppData.message || 'Bonjour de Vue.js !');
                const inputValue = ref('');

                // Fonction pour changer le message via l'input
                const updateMessage = () => {
                    message.value = inputValue.value;
                };

                return {
                    message,
                    inputValue,
                    updateMessage
                };
            },
            template: `
                <div>
                    <h3>{{ message }}</h3>
                    <p>Ceci est une application Vue.js intégrée à WordPress.</p>
                    <input type="text" v-model="inputValue" @keyup.enter="updateMessage" placeholder="Entrez un nouveau message" />
                    <button @click="updateMessage" style="
                        background-color: #42b983; /* Vert Vue.js */
                        color: white;
                        padding: 10px 15px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        margin-left: 10px;
                        font-size: 1em;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                        transition: background-color 0.3s ease;
                    ">
                        Mettre à jour le message
                    </button>
                </div>
            `
        }).mount('#demi-sel-root');
    } else {
        console.warn('Vue.js App Root ou les données wp_localize_script non trouvés. L\'application Vue ne sera pas montée.');
    }
});