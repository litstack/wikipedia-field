<template>
    <lit-base-field :field="field">
        <div class="w-100 lit-block">
            <b-form-group
                description="https://de.wikipedia.org/wiki/PHP"
                label="URL"
                :invalid-feedback="url_errors"
                :state="url_errors == null"
            >
                <b-input class="mb-2 rounded-full" v-model="formData.url" />
            </b-form-group>
            <b-form-group
                description="Geschichte"
                label="Section"
                v-if="field.section"
            >
                <b-input class="mb-2 rounded-full" v-model="formData.section" />
            </b-form-group>
            <b-form-group
                description="Chars"
                label="Max Chars"
                v-if="field.chars"
            >
                <b-input
                    class="mb-2 rounded-full"
                    v-model="formData.chars"
                    type="number"
                />
            </b-form-group>
            <b-button @click="preview()" size="sm">
                <i class="fa fa-eye" aria-hidden="true"></i> Preview
                <b-spinner small type="grow" v-if="busy"></b-spinner>
            </b-button>
            <b-modal v-model="show_preview" hide-header cancel-disabled>
                <div v-html="preview_text"></div>
            </b-modal>
        </div>
    </lit-base-field>
</template>

<script>
export default {
    name: 'LitWikipedia',
    props: {
        field: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
        model: {
            type: Object,
        },
    },
    data() {
        return {
            busy: false,
            errors: [],
            formData: {
                url: null,
                section: null,
                chars: null,
            },
            changed: false,
            show_preview: false,
            preview_text: null,
            is_watching: false,
        };
    },
    beforeMount() {
        this.init();

        this.is_watching = true;

        Lit.bus.$on('saved', () => {
            if (!this.changed) {
                return;
            }

            Lit.bus.$emit('reload:model');
        });
    },
    watch: {
        value(val) {
            this.init();
        },
        formData: {
            deep: true,
            handler(val) {
                this.input(this.formData);
            },
        },
    },
    methods: {
        init() {
            if (this.value) {
                if (typeof this.value === 'string') {
                    this.formData = JSON.parse(this.value);
                } else {
                    this.formData = this.value;
                }
            } else {
                this.formData = {
                    url: null,
                    section: null,
                    chars: null,
                };
            }
        },
        input(val) {
            this.changed = true;
            this.$emit('input', val);
        },
        async preview() {
            this.busy = true;
            try {
                const { data } = await axios.post(
                    'wikipedia-preview',
                    this.formData
                );
                this.preview_text = data;
                this.show_preview = true;
            } catch (error) {
                this.errors = error.response.data.errors;
            } finally {
                this.busy = false;
            }
        },
    },
    computed: {
        url_errors() {
            return this.errors.url?.join(', ');
        },
    },
};
</script>
