<template>
    <div>
        <ManageArticles/>
        <v-main>
            <AdminDashboard/>
        </v-main>
    </div>
</template>

<script>
import AdminDashboard from '@/components/admin/AdminDashboard.vue'
import ManageArticles from '@/components/admin/ManageArticles.vue'

export default {
    components:{
        AdminDashboard, ManageArticles
    }
}
</script>