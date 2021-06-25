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
            <b-form-group description="Geschichte" label="Section">
                <b-input
                    class="mb-2 rounded-full"
                    v-model="formData.section"
                    v-if="hasSection"
                />
            </b-form-group>
            <b-form-group description="Chars" label="Max Chars">
                <b-input
                    class="mb-2 rounded-full"
                    v-model="formData.chars"
                    v-if="hasChars"
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
        };
    },
    beforeMount() {
        this.init();

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
                this.formData = this.value;
            } else {
                this.formData = {
                    url: this.model[this.field.url_key],
                    section: this.model[this.field.section_key],
                    chars: this.model[this.field.chars_key],
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
                const { data } = await axios.post('wikipedia-preview', {
                    url: this.formData.url,
                    section: this.formData.section,
                    chars: this.formData.chars,
                });
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
        hasSection() {
            return this.field.hasOwnProperty('section_key');
        },
        hasChars() {
            return this.field.hasOwnProperty('chars_key');
        },
        url_errors() {
            return this.errors.url?.join(', ');
        },
    },
};
</script>
