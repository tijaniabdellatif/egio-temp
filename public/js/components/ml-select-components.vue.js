const MlSelect = {
    /*html*/
    // template: `
    //     <div class="ml-select">
    //         <input type="text" :id="'input_'+id" :class="mlsClass" @input="checkIfMatch($event)" onfocus="this.select()" @blur="focus" :placeholder="mlsPlaceholder" :disabled="disabled" >
    //         <div class="ml-select-options">
    //             <template v-for="(option,index) in serchedOptions">
    //                 <div class="ml-select-option" :class="selectedOption==option[value]?'ml-selected-option':''" @mousedown="select(option,$event)">
    //                     {{ option[label] }}
    //                 </div>
    //             </template>
    //         </div>
    //     </div>
    // `,
    template: `
    <div class="ml-select">
        <!--<input type="text" :id="'input_'+id" class="ml-select-input" :class="mlsClass" @input="checkIfMatch($event)" :placeholder="mlsPlaceholder" :disabled="disabled" >-->
        <div class="ml-select-input" :id="'input_'+id" :class="mlsClass" @click="show=!show" style="min-height: 32px;cursor: pointer;">
        {{ selectedText()??mlsPlaceholder }}
        </div>
        <div class="ml-select-options" :class="show?'':'d-none'" style="padding-top: 0;">
            <div class="ml-select-option" style="position: sticky;top: 0;background: white;padding-top: 5px;">
                <input :id="'search_'+id" style="width: 100%;" class="ml-select-search" type="text" @input="checkIfMatch($event)">
            </div>
            <template v-for="(option,index) in serchedOptions">
                <option class="ml-select-option" :class="selectedOption==option[value]?'ml-selected-option':''" @mousedown="select(option,$event)">
                    {{ option[label] }}
                </option>
            </template>
        </div>
    </div>
`,
    props: {
        mlsClass: {
            type: String,
            default: ''
        },
        mlsPlaceholder: {
            type: String,
            default: ''
        },
        options: {
            type: Array,
            default: () => []
        },
        label: {
            type: String,
            default: 'label'
        },
        value: {
            type: String,
            default: 'value'
        },
        disabled:{
            default: false
        },
        selectedOption: {
            default: null
        }
    },
    emits: ['update:selectedOption'],
    data() {
        return {
            id: '',
            serchedOptions: [],
            show: false,
            text: '',
        }
    },
    computed: {},
    watch: {
        options: function(newVal, oldVal) {
            this.serchedOptions=newVal;
            this.$emit("change");

            this.text="";
            for(const o of this.options){
                if(o.id==this.selectedOption){
                    this.text=o[this.label];
                }
            }
        }
    },
    beforeMount(){
        // generate unique id
        this.id = Math.random().toString(36).substr(2, 9);
    },
    mounted() {
        this.serchedOptions = this.options;
        for(const o of this.options){
            if(o.id==this.selectedOption){
                this.text=o[this.label];
            }
        }
        $(window).click(()=>{
            this.show = false;
        });

        $('#search_'+this.id).click((event)=>{
            event.stopPropagation();
            this.show = true;
        });
        $('#input_'+this.id).click((event)=>{
            event.stopPropagation();
        });

    },
    methods: {
        selectedText(){
            let text = '';
            for(const o of this.options){
                if(o[this.value]==this.selectedOption){
                    text=o[this.label];
                }
            }
            if(text!='') return text;
            else return this.mlsPlaceholder;
        },
        select(option,e) {
            e.preventDefault();
            // check if option is exist in options array
            if (option != null) {

                if (!this.options.find(o => o[this.value] == option[this.value]))
                    return;
                console.log('selected:',option[this.value]);
                /*if (this.selectedOption == option) {
                    this.$emit('update:selectedOption', null);
                    return;
                }*/
            }

            this.$emit('update:selectedOption', option[this.value]);
            this.$emit("change");

            if(option != null) {
                this.text = option[this.label];
            }
            else {
                this.text = '';
            }

        },
        checkIfMatch(e) {

            let input = e.target.value;
            let options = this.options;

            if (this.selectedOption != null)
                if (input != this.selectedOption) {
                    this.$emit('update:selectedOption', null);
                    this.$emit("change");
                }

            this.serchedOptions = options.filter(option => {
                return option[this.label].toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").includes(input.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, ""));
            });

        },
        focus(e) {
            let input = e.target.value;
            // check if the value is exist in options
            let option = this.options.find(option => option[this.label] == input);
            if (option == null) {
                //this.$emit('update:selectedOption', null);
                e.target.value = '';
                this.checkIfMatch(e);
            }

        },
    }
};
