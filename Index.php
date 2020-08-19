<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Patient table</title>
  <link href="/assets/css/style.css" rel="stylesheet" />
  <script src="/assets/js/d3.js"></script>
  <style>
    rect {
      fill: steelblue;
      fill-opacity: 0.8;
    }

    rect:hover {
      fill-opacity: 1;
    }

    .axis {
      font-size: smaller;
    }
  </style>
</head>

<body>
  <h1>Student's First Barchart</h1>

  <!--html filter checkbox-->
  <div>
    <strong>Filter:</strong>
    <label><input type="checkbox" name="US" value="1" id="filter-us-only" />US only</label>
  </div>
  
  <script type="text/javascript">
    const margin = { top: 40, bottom: 10, left: 120, right: 20 };
    const width = 800 - margin.left - margin.right;
    const height = 600 - margin.top - margin.bottom;

    // Creates sources <svg> element
    const svg = d3
      .select("body")
      .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom);

    // Group used to enforce margin
    const g = svg.append("g").attr("transform", `translate(${margin.left},${margin.top})`);

    // Global variable for all data
    let data;

    // Scales setup
    const xscale = d3.scaleLinear().range([0, width]);
    const yscale = d3.scaleBand().rangeRound([0, height]).paddingInner(0.1);

    // Axis setup
    const xaxis = d3.axisTop().scale(xscale);
    const g_xaxis = g.append("g").attr("class", "x axis");
    const yaxis = d3.axisLeft().scale(yscale);
    const g_yaxis = g.append("g").attr("class", "y axis");

    /////////////////////////
    // TODO use the provided code sample (see html checkbox and change handler)
    // when checked just the entries where `location.country === 'US'` should be shown

    d3.json("/data/data.json").then((json) => {
      data = json;
      console.log(data);
      update(data);
    });

    function update(new_data) {
      //update the scales
      xscale.domain([0, 100]);
      yscale.domain(new_data.map((d) => d['risk group']));
      //render the axis
      g_xaxis.call(xaxis);
      g_yaxis.call(yaxis);

      // Render the chart with new data

      // DATA JOIN
      const rect = g
        .selectAll("rect")
        .data(new_data)
        .join(
          // ENTER
          // new elements
          (enter) => {
            const rect_enter = enter.append("rect").attr("x", 0);
            rect_enter.append("title");
            return rect_enter;
          },
          // UPDATE
          // update existing elements
          (update) => update,
          // EXIT
          // elements that aren't associated with data
          (exit) => exit.remove()
        );

      // ENTER + UPDATE
      // both old and new elements
      rect
        .attr("height", yscale.bandwidth())
        .attr("width", (d) => xscale(d['vaccinated']*100/d['registered']))
        .attr("y", (d) => yscale(d['risk group']));
      
      rect.select("title").text((d) => d['risk group']);
    }

    //interactivity
    d3.select("#filter-us-only").on("change", function () {
      // This will be triggered when the user selects or unselects the checkbox
      const checked = d3.select(this).property("checked");
    });
  </script>
</body>

</html>