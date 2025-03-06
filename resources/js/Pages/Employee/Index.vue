<template>
    <div class="p-4">
        <a-page-header title="Employees" />
        <a-space class="w-full flex justify-between mb-4">
            <a-button type="default" @click="goBack">
                <template #icon><home-outlined /></template>
                Back to Dashboard
            </a-button>
            <a-button type="primary" @click="addEmployee">
                <template #icon><plus-outlined /></template>
                Add Employee
            </a-button>
        </a-space>
<!--        <a-button type="primary" @click="addEmployee" class="mb-4">-->
<!--            <template #icon><plus-outlined /></template>-->
<!--            Add Employee-->
<!--        </a-button>-->

        <a-table :dataSource="employees.data" :columns="columns" rowKey="id" bordered>
            <template #bodyCell="{ column, record, index }">
                <template v-if="column.dataIndex === 'index'">
                    {{ index + 1 }}
                </template>
                <template v-else-if="column.dataIndex === 'full_name'">
                    {{ record.first_name }} {{ record.last_name }}
                </template>
                <template v-else-if="column.dataIndex === 'company'">
                    <a @click="openCompanyModal(record.company)">{{ record.company?.name }}</a>
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <a-space>
                        <a-button type="link" @click="editEmployee(record)">Edit</a-button>
                        <a-divider type="vertical" />
                        <a-popconfirm title="Sure to delete?" @confirm="deleteEmployee(record.id)">
                            <a-button type="link" danger>Delete</a-button>
                        </a-popconfirm>
                    </a-space>
                </template>
            </template>
        </a-table>

        <a-modal v-model:visible="isEditModalOpen" title="Edit Employee" @ok="updateEmployee">
            <a-form layout="vertical">
                <a-form-item label="First Name">
                    <a-input v-model:value="modalData.first_name" />
                </a-form-item>
                <a-form-item label="Last Name">
                    <a-input v-model:value="modalData.last_name" />
                </a-form-item>
                <a-form-item label="Email">
                    <a-input v-model:value="modalData.email" type="email" />
                </a-form-item>
                <a-form-item label="Phone">
                    <a-input v-model:value="modalData.phone" />
                </a-form-item>
                <a-form-item label="Company">
                    <a-select v-model:value="modalData.company_id" placeholder="Select Company">
                        <a-select-option v-for="company in companies" :key="company.id" :value="company.id">
                            {{ company.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-form>
        </a-modal>

        <a-modal v-model:visible="showCompanyDetails" title="Company Details" @ok="closeCompanyModal">
            <p><strong>Name:</strong> {{ showCompanyDetails?.name }}</p>
            <p><strong>Email:</strong> {{ showCompanyDetails?.email }}</p>
            <p><strong>Website:</strong> <a :href="showCompanyDetails?.website" target="_blank">{{ showCompanyDetails?.website }}</a></p>
        </a-modal>
    </div>
</template>

<script>
import { PlusOutlined } from '@ant-design/icons-vue';
export default {
    components: {
        PlusOutlined,
    },
    props: {
        employees: Object,
        companies: Array,
    },
    data() {
        return {
            isEditModalOpen: false,
            showCompanyDetails: null,
            modalData: {
                id: null,
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                company_id: null,
            },
            columns: [
                { title: 'Index', dataIndex: 'index' },
                { title: 'Full Name', dataIndex: 'full_name' },
                { title: 'Company', dataIndex: 'company' },
                { title: 'Email', dataIndex: 'email' },
                { title: 'Phone', dataIndex: 'phone' },
                { title: 'Action', dataIndex: 'action' },
            ],
        };
    },
    methods: {
        addEmployee() {
            this.$inertia.visit(route('employees.create'));
        },
        editEmployee(employee) {
            this.modalData = { ...employee, company_id: employee.company?.id || null };
            this.isEditModalOpen = true;
        },
        async updateEmployee() {
            const fullName = `${this.modalData.first_name} ${this.modalData.last_name}`.trim();
            try {
                await this.$inertia.put(route('employees.update', this.modalData.id), {
                    full_name: fullName,
                    email: this.modalData.email,
                    phone: this.modalData.phone,
                    company_id: this.modalData.company_id,
                });
                this.isEditModalOpen = false;
            } catch (error) {
                console.error('Error updating employee:', error);
            }
        },
        deleteEmployee(id) {
            this.$inertia.delete(route('employees.destroy', id));
        },
        openCompanyModal(company) {
            this.showCompanyDetails = company;
        },
        closeCompanyModal() {
            this.showCompanyDetails = null;
        },
        goBack() {
            this.$inertia.visit(route('dashboard'));
        },
    },
};
</script>
