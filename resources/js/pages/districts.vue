<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-12">
				<div class="card">
					<div class="card-header card-header-tabs card-header-primary">
						<div class="nav-tabs-navigation">
							<div class="nav-tabs-wrapper">
								<h4 class="card-title">Districts</h4>
							</div>
						</div>
					</div>
					<div class="card-body table-responsive">
						<table class="table table-hover">
							<thead class="text-warning">
								<th>ID</th>
								<th>Name</th>
							</thead>
							<tbody>
								<tr v-for="(item, index) in districts" :key="'div' + index">
									<td>{{ item.id }}</td>
									<td>{{ item.name }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>