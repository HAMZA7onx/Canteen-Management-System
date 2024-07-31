<template>
    <div ref="chartContainer" class="w-full h-64"></div>
  </template>
  
  <script>
  import { ref, onMounted, watch } from 'vue';
  import * as d3 from 'd3';
  
  export default {
    props: {
      data: Array,
    },
    setup(props) {
      const chartContainer = ref(null);
  
      const drawChart = () => {
        const width = 300;
        const height = 300;
        const radius = Math.min(width, height) / 2;
  
        const svg = d3.select(chartContainer.value)
          .append('svg')
          .attr('width', width)
          .attr('height', height)
          .append('g')
          .attr('transform', `translate(${width / 2},${height / 2})`);
  
        const color = d3.scaleOrdinal()
          .domain(props.data.map(d => d.name))
          .range(d3.schemeCategory10);
  
        const pie = d3.pie()
          .value(d => d.value);
  
        const arc = d3.arc()
          .innerRadius(0)
          .outerRadius(radius);
  
        const arcs = svg.selectAll('arc')
          .data(pie(props.data))
          .enter()
          .append('g');
  
        arcs.append('path')
          .attr('d', arc)
          .attr('fill', d => color(d.data.name));
  
        arcs.append('text')
          .attr('transform', d => `translate(${arc.centroid(d)})`)
          .attr('text-anchor', 'middle')
          .text(d => d.data.name);
      };
  
      onMounted(drawChart);
  
      watch(() => props.data, drawChart);
  
      return { chartContainer };
    }
  }
  </script>
  