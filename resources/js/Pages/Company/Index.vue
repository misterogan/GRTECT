<template>
    <div class="p-4">
        <a-page-header title="Companies" />

        <a-space class="w-full flex justify-between mb-4">
            <a-button type="default" @click="goBack">
                <template #icon><home-outlined /></template>
                Back to Dashboard
            </a-button>
            <a-button type="primary" @click="createCompany">
                <template #icon><plus-outlined /></template>
                Add Company
            </a-button>
        </a-space>

        <a-table :dataSource="companies.data" :columns="columns" rowKey="id" bordered>
            <template #bodyCell="{ column, record, index }">
                <template v-if="column.dataIndex === 'index'">
                    {{ index + 1 }}
                </template>
                <template v-else-if="column.dataIndex === 'logo'">
                    <a-avatar :src="`/storage/${record.logo}`" size="large" />
                </template>
                <template v-else-if="column.dataIndex === 'website'">
                    <a :href="record.website" target="_blank">Visit</a>
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <a-space>
                        <a-button type="link" @click="openEditModal(record)">Edit</a-button>
                        <a-divider type="vertical" />
                        <a-popconfirm title="Are you sure to delete?" @confirm="deleteCompany(record.id)">
                            <a-button type="link" danger>Delete</a-button>
                        </a-popconfirm>
                    </a-space>
                </template>
            </template>
        </a-table>


        <a-modal v-model:visible="isModalOpen" title="Edit Company" @ok="updateCompany">
            <a-form layout="vertical">
                <a-form-item label="Name" :validate-status="errors.name ? 'error' : ''" :help="errors.name">
                    <a-input v-model:value="modalData.name" />
                </a-form-item>
                <a-form-item label="Email" :validate-status="errors.email ? 'error' : ''" :help="errors.email">
                    <a-input v-model:value="modalData.email" type="email" />
                </a-form-item>
                <a-form-item label="Website" :validate-status="errors.website ? 'error' : ''" :help="errors.website">
                    <a-input v-model:value="modalData.website" type="url" />
                </a-form-item>
            </a-form>
        </a-modal>
    </div>
</template>

<script>
import { PlusOutlined,  HomeOutlined, UploadOutlined } from '@ant-design/icons-vue';
export default {
    components: {
        PlusOutlined,
        UploadOutlined,
        HomeOutlined
    },
    props: {
        companies: Object,
        errors: Object,
    },
    data() {
        return {
            errors: {},
            isModalOpen: false,
            modalData: {
                id: null,
                name: '',
                email: '',
                website: '',
                logo: null,
                newLogo: null,
            },
            columns: [
                { title: 'Index', dataIndex: 'index' },
                { title: 'Name', dataIndex: 'name' },
                { title: 'Email', dataIndex: 'email' },
                { title: 'Logo', dataIndex: 'logo' },
                { title: 'Website', dataIndex: 'website' },
                { title: 'Action', dataIndex: 'action' },
            ],
        };
    },
    methods: {
        addCompany() {
            this.$inertia.visit(route('companies.create'));
        },
        backtoDashboard() {
            this.$inertia.visit(route('dashboard'));
        },
        openEditModal(company) {
            this.modalData = { ...company };
            this.isModalOpen = true;
        },
        async updateCompany() {

            try {
                await this.$inertia.put(route('companies.update', this.modalData.id), {
                    name: this.modalData.name,
                    email: this.modalData.email,
                    website: this.modalData.website,
                }, {
                    preserveState: true,
                    onError: (errors) => {
                        this.errors = errors;
                        this.isModalOpen = true;
                    },
                    onSuccess: () => {
                        this.errors = {};
                        this.isModalOpen = false;
                    },
                });
            } catch (error) {
                console.error('Error updating company:', error);
            }
        },
        deleteCompany(id) {
            this.$inertia.delete(route('companies.destroy', id));
        },
        handleLogoChange(file) {
            this.modalData.newLogo = file;
            this.modalData.logo = URL.createObjectURL(file);
            return false;
        },
        getLogoUrl(logo) {
            if (!logo) return null;
            if (logo instanceof File) {
                return URL.createObjectURL(logo);
            }
            if (logo.startsWith('blob:')) {
                return logo;
            }
            if (logo.startsWith('http') || logo.startsWith('/storage/')) {
                return logo;
            }
            return `/storage/${logo}`;
        },
        createCompany(){
            this.$inertia.visit(route('companies.create'));
        },
        goBack() {
            this.$inertia.visit(route('dashboard'));
        },
    },
};
</script>
