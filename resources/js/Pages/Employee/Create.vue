<template>
    <div class="container mx-auto p-4 max-w-lg">
        <a-page-header title="Add New Employee" />

        <a-form layout="vertical" @submit.prevent="createEmployee">
            <a-form-item label="Full Name" :validate-status="errors.full_name ? 'error' : ''" :help="errors.full_name">
                <a-input v-model:value="form.full_name" placeholder="Enter full name" />
            </a-form-item>

            <a-form-item label="Email" :validate-status="errors.email ? 'error' : ''" :help="errors.email">
                <a-input v-model:value="form.email" type="email" placeholder="Enter email" />
            </a-form-item>

            <a-form-item label="Phone" :validate-status="errors.phone ? 'error' : ''" :help="errors.phone">
                <a-input v-model:value="form.phone" type="text" placeholder="Enter phone number" />
            </a-form-item>

            <a-form-item label="Company" :validate-status="errors.company_id ? 'error' : ''" :help="errors.company_id">
                <a-select v-model:value="form.company_id" placeholder="Select Company">
                    <a-select-option v-for="company in companies" :key="company.id" :value="company.id">
                        {{ company.name }}
                    </a-select-option>
                </a-select>
            </a-form-item>

            <a-form-item>
                <a-space class="w-full flex justify-between">
                    <a-button type="default" @click="goBack">
                        Back to Employees
                    </a-button>
                    <a-button type="primary" html-type="submit">
                        Add Employee
                    </a-button>
                </a-space>
            </a-form-item>
        </a-form>
    </div>
</template>

<script>
import { HomeOutlined, PlusOutlined } from '@ant-design/icons-vue';
export default {
    components: {
        HomeOutlined,
        PlusOutlined,
    },
    data() {
        return {
            form: {
                full_name: '',
                email: '',
                phone: '',
                company_id: null,
            },
            companies: [], // List of companies
            errors: {},
        };
    },
    mounted() {
        this.fetchCompanies();
    },
    methods: {
        async fetchCompanies() {
            try {
                const response = await axios.get('/api/companies');
                this.companies = response.data;
            } catch (error) {
                console.error('Error fetching companies:', error);
            }
        },
        async createEmployee() {
            const formData = new FormData();
            formData.append('full_name', this.form.full_name);
            formData.append('email', this.form.email);
            formData.append('phone', this.form.phone);
            formData.append('company_id', this.form.company_id);

            try {
                await this.$inertia.post(route('employees.store'), formData, {
                    preserveState: true,
                    onError: (errors) => {
                        this.errors = errors;
                    },
                    onSuccess: () => {
                        this.form = {
                            full_name: '',
                            email: '',
                            phone: '',
                            company_id: null,
                        };
                        this.errors = {};
                    },
                });
            } catch (error) {
                console.error('Error creating employee:', error);
            }
        },
        goBack() {
            this.$inertia.visit(route('employees.index'));
        },
    },
};
</script>
