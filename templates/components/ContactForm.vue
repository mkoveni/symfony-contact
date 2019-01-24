<template>
    <div class="card">
        <div class="card-header">
            <h4>Contact Us</h4>
        </div>

        <div class="card-body">
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for>Name</label>

                    <input type="text" class="form-control" v-model="form.name" :class="{'is-invalid': errors.name}">

                    <strong v-if="errors.name" class="text-danger">{{ errors.name[0] }}</strong>
                </div>

                <div class="form-group">
                    <label for>Email</label>

                    <input type="email" class="form-control" v-model="form.email" :class="{'is-invalid': errors.email}">

                    <strong v-if="errors.email" class="text-danger">{{ errors.email[0] }}</strong>
                </div>

                <div class="form-group">
                    <label for>Message</label>

                    <textarea class="form-control" rows="5" v-model="form.message" :class="{'is-invalid': errors.message}"></textarea>

                    <strong v-if="errors.message" class="text-danger">{{ errors.message[0] }}</strong>
                </div>

                <vue-recaptcha sitekey="6LdYoIsUAAAAAIUdADoUJXHmksUlnecknFR_BkmC" ref="recaptcha" v-model="captcha"
                    @verify="canSubmit = true" @expired="reset" />

                <div class="form-group"></div>

                <div class="form-group">
                    <button :disabled="!canSubmit" class="btn btn-info btn-block">Message</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import axios from "axios";
    import VueRecaptcha from "vue-recaptcha";

    export default {
        components: {
            VueRecaptcha
        },
        data() {
            return {
                form: {
                    name: "",
                    email: "",
                    message: ""
                },
                captcha: null,
                canSubmit: false,
                errors: {}
            };
        },

        methods: {
            async submit() {
                try {
                    const response = await axios.post("/api/contact", this.form)

                    this.form.name = ''
                    this.form.email = ''
                    this.form.message = ''

                    await this.$swal({
                        title: "Message Sent",
                        text: 'Message was sent successfully',
                        type: "success",
                        showCancelButton: false,
                        showLoaderOnConfirm: true,
                        showCloseButton: false
                    });

                    this.errors = {}

                } catch (e) {
                    if (e.response && e.response.status === 422) {
                        this.errors = e.response.data.errors
                    }


                    await this.$swal({
                        title: "Oops!",
                        text: 'Could not sent your message.',
                        type: "error",
                        showCancelButton: false,
                        showLoaderOnConfirm: true,
                        showCloseButton: false
                    });

                }
                this.reset();
            },
            reset() {
                this.canSubmit = false;
                this.$refs.recaptcha.reset()
            }
        }
    };
</script>