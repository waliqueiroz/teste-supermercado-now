<template>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item active"><a role="tab" class="nav-link active" data-toggle="tab" href="#pending">Pendentes</a></li>
                <li class="nav-item"><a role="tab" class="nav-link" data-toggle="tab" href="#under-analysis">Em análise</a></li>
                <li class="nav-item"><a role="tab" class="nav-link" data-toggle="tab" href="#approved">Aprovados</a></li>
                <li class="nav-item"><a role="tab" class="nav-link" data-toggle="tab" href="#disapproved">Reprovados</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div id="pending" class="tab-pane show fade in active">
                    <tab-content @updated="refreshUnderAnalysis()" ref="pending" :status-id="status.PENDING"></tab-content>
                </div>
                <div id="under-analysis" class="tab-pane fade">
                    <tab-content @updated="refreshApprovedAndDisapproved()" ref="underAnalysis" :status-id="status.UNDER_ANALYSIS"></tab-content>
                </div>
                <div id="approved" class="tab-pane fade">
                    <tab-content ref="approved" :status-id="status.APPROVED"></tab-content>
                </div>
                <div id="disapproved" class="tab-pane fade">
                    <tab-content ref="disapproved" :status-id="status.DISAPPROVED"></tab-content>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TabContent from "./components/TabContent";
import { status } from "@/utils/constants";
export default {
    components: { TabContent },
    computed: {
        status: function() {
            return status;
        }
    },
    data() {
        return {};
    },
    mounted() {},
    methods: {
        refreshUnderAnalysis() {
            this.$refs.underAnalysis.getProducts();
        },
        refreshApprovedAndDisapproved() {
            this.$refs.approved.getProducts();
            this.$refs.disapproved.getProducts();
        }
    }
};
</script>

<style>
</style>