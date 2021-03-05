<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-header-warning">
						<h4 class="card-title">Employees Stats</h4>
						<p class="card-category">New employees on 15th September, 2016</p>
					</div>
					<div class="card-body" style="overflow: auto">
						<div class="d-flex justify-content-center" v-if="isLoading">
							<Loader />
						</div>
						<div
							class="d-flex justify-content-center"
							v-if="!isLoading && isEmpty"
						>
							<p>Tree not found!</p>
						</div>
						<template v-if="!isLoading && !isEmpty">
							<ul class="tree">
								<li v-if="treeData">
									<tree :node="treeData"></tree>
								</li>
							</ul>
						</template>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Tree from "../../components/tree";
export default {
	components: {
		Tree,
	},

	data() {
		return {
			isLoading: true,
			treeData: {},
			isEmpty: false,
		};
	},

	methods: {
		async getTree(id) {
			this.isLoading = true;
			const res = await this.callApi("get", `/app/customer/get/tree/${id}`);
			if (res.status == 200) {
				this.treeData = res.data;
			}
			if (res.status == 404) {
				this.isEmpty = true;
			}
			this.isLoading = false;
		},
	},
	async created() {
		this.getTree(this.$route.params.id);
	},

	watch: {
		"$route.params.id": function () {
			this.getTree(this.$route.params.id);
		},
	},
};
</script>