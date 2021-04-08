<template>
	<div
		:style="
			position == 'center'
				? 'width: 100%; height: 100%; text-align: center; cursor: progress'
				: 'width: 40px; float: left; cursor: progress;'
		"
	>
		<svg
			v-if="position == 'inline'"
			x="0px"
			y="0px"
			viewBox="0 0 100 100"
			enable-background="new 0 0 0 0"
			xml:space="preserve"
		>
			<path
				:fill="!color ? '#555555' : color"
				d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"
			>
				<animateTransform
					attributeName="transform"
					attributeType="XML"
					type="rotate"
					dur="1s"
					from="0 50 50"
					to="360 50 50"
					repeatCount="indefinite"
				/>
			</path>
		</svg>

		<svg
			v-else
			style="width: 50px; margin: 0 auto"
			x="0px"
			y="0px"
			viewBox="0 0 100 100"
			enable-background="new 0 0 0 0"
			xml:space="preserve"
		>
			<path
				:fill="!color ? '#555555' : color"
				d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"
			>
				<animateTransform
					attributeName="transform"
					attributeType="XML"
					type="rotate"
					dur="1s"
					from="0 50 50"
					to="360 50 50"
					repeatCount="indefinite"
				/>
			</path>
		</svg>
		<!-- <Spin fix>
			<Icon type="ios-loading" size="18" class="demo-spin-icon-load"></Icon>
			<div>Loading</div>
		</Spin> -->
		<!-- <img src="/img/loader_sm.gif" alt="" /> -->
	</div>
</template>

<script>
export default {
	props: ["position", "color"],
};
</script>


<style>
.demo-spin-icon-load {
	animation: ani-demo-spin 1s linear infinite;
}
@keyframes ani-demo-spin {
	from {
		transform: rotate(0deg);
	}
	50% {
		transform: rotate(180deg);
	}
	to {
		transform: rotate(360deg);
	}
}
</style>
