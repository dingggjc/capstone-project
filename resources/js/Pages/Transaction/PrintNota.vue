<template>
    <div class="print-nota">
        <div class="title" style="padding-bottom: 13px; text-align: center">
            <h2 style="text-transform: uppercase; font-size: 15px">Gardenside Carwash</h2>
            <p>Address: Tagoloan Misamis Oriental</p>
            <p>Phone:0991-7536-321</p>
        </div>

        <div class="separate-line" style="border-top: 1px dashed #000; height: 1px; margin-bottom: 5px"></div>

        <table class="transaction-info">
            <tr>
                <td>Date</td>
                <td>:</td>
                <td>{{ transaction.created_at }}</td>
            </tr>
            <tr>
                <td>Invoice</td>
                <td>:</td>
                <td>{{ transaction.invoice }}</td>
            </tr>
            <tr>
                <td>Cashier</td>
                <td>:</td>
                <td>{{ transaction.cashier ? transaction.cashier.name : 'N/A' }}</td>
            </tr>
            <tr>
                <td>Customer</td>
                <td>:</td>
                <td>{{ transaction.customer_name }}</td>
            </tr>

            <tr>
                <td>Plate Number</td>
                <td>:</td>
                <td>{{ transaction.vehicle_plate }}</td>
            </tr>
        </table>

        <div class="transaction">
            <div class="title" style="padding-bottom: 13px; padding-top: 10px; text-align: center">
                <h2 style="text-transform: uppercase; font-size: 13px">Transaction Details</h2>

            </div>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center">Quantity</th>
                        <th style="text-align: right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="detail in transaction.details" :key="detail.id">
                        <!-- Row for Product -->
                        <td v-if="detail.product">
                            {{ detail.product.product_name }}
                        </td>
                        <td v-if="detail.product" style="text-align: center">{{ detail.qty }}</td>
                        <td v-if="detail.product" style="text-align: right">
                            {{ formatCurrency(detail.product_price) }}
                        </td>
                    </tr>
                    <tr v-for="detail in transaction.details" :key="detail.id + '_package'">
                        <!-- Row for Package -->
                        <td v-if="detail.package">
                            {{ detail.package.package_name }}
                        </td>
                        <td></td>
                        <td v-if="detail.package" style="text-align: right">
                            {{ formatCurrency(detail.package_price) }}
                        </td>
                    </tr>
                </tbody>




                <tfoot>
                    <tr>
                        <td colspan="3">
                            <div class="separate-line"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>SUB TOTAL</td>
                        <td>:</td>
                        <td style="text-align: right">{{ formatCurrency(transaction.grand_total) }}</td>
                    </tr>
                    <tr>
                        <td>CASH</td>
                        <td>:</td>
                        <td style="text-align: right">{{ formatCurrency(transaction.cash) }}</td>
                    </tr>
                    <tr>
                        <td>CHANGE</td>
                        <td>:</td>
                        <td style="text-align: right">{{ formatCurrency(transaction.change) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="thanks">
            =====================================
            <p>THANK YOU FOR YOUR VISIT</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        transaction: Object,
    },
    methods: {
        printPage() {
            window.print();
        },
        formatCurrency(amount) {
            return new Intl.NumberFormat().format(amount);
        }
    }
};
</script>


<style scoped>
.print-nota {
    width: 80mm;
    font-family: "Verdana";
    font-size: 10px;
    padding: 20px;
}

.title {
    text-align: center;
}

.separate-line {
    height: 1px;
    border-top: 1px dashed #000;
}

.transaction-table {
    width: 100%;
    font-size: 10px;
}

.transaction-table th,
.transaction-table td {
    padding: 5px 0;
}

.thanks {
    text-align: center;
    margin-top: 20px;
}

@media print {
    @page {
        width: 80mm;
        margin: 0mm;
    }
}

h3 {
    align-items: center;
}
</style>
