<template>
    <div class="container mx-auto p-4 max-w-lg">
        <a-page-header title="Add New Company" />

        <a-form layout="vertical" @submit.prevent="createCompany">
            <a-form-item label="Company Name" :validate-status="errors.name ? 'error' : ''" :help="errors.name">
                <a-input v-model:value="form.name" placeholder="Enter company name" required />
            </a-form-item>

            <a-form-item label="Email" :validate-status="errors.email ? 'error' : ''" :help="errors.email">
                <a-input v-model:value="form.email" type="email" placeholder="Enter email" required />
            </a-form-item>

            <a-form-item label="Logo" :validate-status="errors.logo ? 'error' : ''" :help="errors.logo">
                <a-upload :beforeUpload="handleFileChange" :showUploadList="false">
                    <a-button>
                        <upload-outlined /> Click to Upload
                    </a-button>
                </a-upload>
                <div v-if="form.logoPreview" class="mt-2 text-center">
                    <a-avatar :src="form.logoPreview" size="large" />
                </div>
            </a-form-item>

            <a-form-item label="Website" :validate-status="errors.website ? 'error' : ''" :help="errors.website">
                <a-input v-model:value="form.website" type="url" placeholder="Enter website URL" required />
            </a-form-item>

            <a-form-item>
                <a-space class="w-full flex justify-between mb-4">
                    <a-button type="default" @click="goBack">
                        Back
                    </a-button>
                    <a-button type="primary" @click="createCompany">
                        <template #icon><plus-outlined /></template>
                        Add Company
                    </a-button>
                </a-space>
            </a-form-item>
        </a-form>
    </div>
</template>

<script>
import { UploadOutlined, HomeOutlined, PlusOutlined } from '@ant-design/icons-vue';
export default {
    components: {
        UploadOutlined,
        HomeOutlined,
        PlusOutlined,
    },
    data() {
        return {
            form: {
                name: '',
                email: '',
                logo: null,
                logoPreview: null,
                website: '',
            },
            errors: {},
        };
    },
    methods: {
        handleFileChange(file) {
            this.form.logo = file;
            this.form.logoPreview = URL.createObjectURL(file);
            return false;
        },
        async createCompany() {
            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('email', this.form.email);
            formData.append('website', this.form.website);
            if (this.form.logo) {
                formData.append('logo', this.form.logo);
            }

            try {
                await this.$inertia.post(route('companies.store'), formData, {
                    preserveState: true,
                    onError: (errors) => {
                        this.errors = errors;
                    },
                    onSuccess: () => {
                        this.form = {
                            name: '',
                            email: '',
                            logo: null,
                            logoPreview: null,
                            website: '',
                        };
                        this.errors = {};
                    },
                });
            } catch (error) {
                console.error('Error creating company:', error);
            }
        },
        goBack() {
            this.$inertia.visit(route('companies.index'));
        },
    },
};
</script>
