var Sector = Backbone.Model.extend({});
var SectorCollection = Backbone.Collection.extend({});
var Region = Backbone.Model.extend({});
var RegionCollection = Backbone.Collection.extend({});
var Project = Backbone.Model.extend({});
var ProjectCollection = Backbone.Collection.extend({
    model: Project,
    sectors: null,
    regions: new RegionCollection(),    
    initialize: function(options) {
        this.url = options.url;
        this.bind('reset', this.setup, this);
        this.setupRegions();
        return this;
    },
    setup: function() {
        this.setupSectors();
        return this;
    },  
    setupSectors: function() {
        var self = this;
        if(!this.sectors) {
            this.sectors = new SectorCollection();
            var tmpsects = _.uniq(_.flatten(this.pluck("sectors")));
            _.each(tmpsects, function(sectorname) {
                self.sectors.add({sector: sectorname});
            });
        }
    },
    resetSectors: function() {
        if(this.sectors) {
            this.sectors.each(function(sector) {
                sector.set({"checked": false});
            });            
        }
    },
    resetRegions: function() {
        if(this.regions) {
            this.regions.each(function(region) {
                region.set({"checked": false});
            });            
        }
    },
    setupRegions: function() {
        if(this.regions.length == 0) {
            var self = this;
            $.ajax({
                url: "/np/regions.json",
                dataType: "json",
                cache: true,
                success: function(items) {
                    _.each(items, function(item) {
                        self.regions.add({region: item});
                    });
                    self.regions.trigger('regionsReady');
                },
                error: function(request, status, error) {
                    // console.log(error);
                }
            });
        }
    },
    getRegionsCollection: function() {
        return this.regions;
    },
    getSectorsCollection: function() {
        return this.sectors;
    },
    filterProjects: function() {
        var selectedSectors = this.sectors.filter(function(sector) {
            return sector.get("checked");
        });
        var selectedRegions = this.regions.filter(function(region) {
            return region.get("checked");
        });
        if(selectedSectors.length == 0 && selectedRegions == 0) {
            return this;
        } else {
            selectedSectorsCollection = new SectorCollection(selectedSectors);
            selectedRegionsCollection = new RegionCollection(selectedRegions);
            return new ProjectCollection(this.models.filter(function(project) {
                var projectSectors = project.get('sectors');
                var found1 = false;
                _.each(projectSectors, function(projectSector) {
                    if(_.contains(selectedSectorsCollection.pluck('sector'), projectSector)) {
                        found1 = true;
                    }
                });
                var projectRegions = project.get('regions');
                var found2 = false;
                _.each(projectRegions, function(projectRegion) {
                    if(_.contains(selectedRegionsCollection.pluck('region'), projectRegion)) {
                        found2 = true;
                    }
                });
                return (found1 || selectedSectors == 0) && (found2 || selectedRegions == 0);
            })).setup();
        }
    },
    groupByRegions: function() {
        _.flatten(this.pluck("regions"))
        return this.groupBy('regions');
    },
    filterProjectsByRegion: function(region) {
        var filteredProjects = this.filterProjects();
        return new ProjectCollection(filteredProjects.models.filter(function(project) {
            return (_.contains(project.get('regions'), region));
        })).setup();
    },
    search: function(letters) {
        if(letters == "") return this;
        var pattern = new RegExp(letters,"gi");
        return new ProjectCollection(this.filter(function(data) {
            return pattern.test(data.get("title"));
        }));
    }
});

var SectorsListView = Backbone.View.extend({
    el: '#sectors',    
    initialize: function(options) {
        this.projectsCollection = options.projectsCollection;
    },
    addSectorItem: function(sector) {
        return new SectorListItemView({model: sector, projectsCollection: this.projectsCollection});
    },
    addAll: function() {
        var self = this;
        this.collection && this.collection.each(function(sector) {
            self.$el.append(self.addSectorItem(sector).render());
        });
        return this.$el;
    },
    render: function() {
        return this.addAll();
    }
});

var SectorListItemView = Backbone.View.extend({
    tagName: 'div',
    events: {
        'click [type="checkbox"]':'clicked'
    },
    initialize: function(options) {
        var self = this;
        this.projectsCollection = options.projectsCollection
        this.model.bind('change:checked', function() {
            if(self.model._previousAttributes.checked == true) {
                var elem = $(self.el).find(':checkbox');
                elem.attr("checked", false);
            }
        });
    },
    clicked: function(e) {
        var isChecked = $(e.currentTarget).is(':checked');
        this.model.set({checked: isChecked});
        this.projectsCollection.trigger('renderAll')

    },
    render: function() {
        var template = _.template($("#sector-checkbox-item").html());
        this.$el.html(template(this.model.toJSON()));
        return this.$el;
    }
});

var RegionListView = Backbone.View.extend({
    el: '#regions',    
    initialize: function(options) {
        this.projectsCollection = options.projectsCollection;
        this.collection.bind('regionsReady', this.render, this);
        this.projectsCollection.bind('select-zanzibar', this.selectZanzibar, this);
    },
    selectZanzibar: function() {
        var zanzibarRegions = ["Dhangadi"];
        this.collection.each(function(model) {
            if(_.contains(zanzibarRegions, model.get("region"))) {
                model.set({"checked": true});
                model.trigger("check-checkbox");
            }
        });
    },
    addItem: function(item) {
        return new RegionListItemView({model: item, projectsCollection: this.projectsCollection});
    },
    addAll: function() {
        var self = this;
        this.collection && this.collection.each(function(item) {
            self.$el.append(self.addItem(item).render());
        });
        return this.$el;
    },
    render: function() {
        return this.addAll();
    }
});

var RegionListItemView = Backbone.View.extend({
    tagName: "div",
    events: {
        'click [type="checkbox"]':'clicked'
    },
    initialize: function(options) {
        var self = this;
        this.projectsCollection = options.projectsCollection;
        this.model.bind('check-checkbox', function() {
            var elem = $(self.el).find(':checkbox');
            elem.attr("checked", true);
        });
        this.model.bind('change:checked', function() {
            if(self.model._previousAttributes.checked == true) {
                var elem = $(self.el).find(':checkbox');
                elem.attr("checked", false);
            }
        });
    },
    clicked: function(e) {
        var isChecked = $(e.currentTarget).is(':checked');
        this.model.set({checked: isChecked});
        this.projectsCollection.trigger('renderAll')
    },
    render: function() {
        var template = _.template($("#region-checkbox-item").html());
        this.$el.html(template(this.model.toJSON()));
        // this.$el.html("<div><input type='checkbox' class='sector-checkbox' />" + this.model.get('region') + "</div>");
        return this.$el;
    }
});

var ProjectsListItemView = Backbone.View.extend({
    tagName: "tr",
    render: function() {
        var template = _.template($("#project-list-item").html());
        this.$el.html(template({"project": this.model.toJSON()}));
        return this.$el
    },
});

var ProjectsListView = Backbone.View.extend({
    el: "#projects-container",
    events: {
        "keyup #projects-search": "search"
    },
    initialize: function() {
        this.searchtext = "";
        this.collection.on('renderAll', this.render, this);
    },
    addOne: function(project) {
        this.$("#data-table tbody").append(new ProjectsListItemView({model: project}).render());
    },
    addAll: function() {
        var self = this;
        var projects = this.collection.filterProjects();
        if(this.searchtext) {
            projects = projects.search(this.searchtext);
        }
        if(!projects.length) {
            this.$("tbody").html("<tr><td class='text-center no-data' colspan='100%'>No projects found.</td></tr>");
        } else {
            this.$("tbody").html("");
            projects.each(function(project) {
                self.addOne(project);
            });
        }
    },
    search: function(e) {
        this.searchtext = $("#projects-search").val();
        this.render();
    },    
    render: function() {
        this.addAll();
    }
});

var MapView = Backbone.View.extend({
    el: '#tzmap',
    className:'',
    initialize: function() {
        this.regionLayers = {}
        this.collection.on('renderAll', this.render, this);
        this.collection.on("zoom-zanzibar", this.zoomZanzibar, this);
        this.map = L.map(document.getElementById("tzmap"), {zoomControl: false}).setView([28.700121,80.535514], 6);
        L.control.zoom({
            position:'topright'
        }).addTo(this.map);
        this.map.scrollWheelZoom.disable();        
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
          attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
          maxZoom: 18
        }).addTo(this.map);
        this.setupRegions();
        this.bindRegions();
    },
    zoomZanzibar: function() {
        this.map.setView([28.700121,80.535514]);
        this.map.setZoom(8);
    },
    setupRegions: function() {
        var self = this;
        tz_regions.features.forEach(function(feature) {
            // console.log(feature.properties.REGNAME);
            self.regionLayers[feature.properties.REGNAME] = L.geoJson({"features":[feature]}, {
                style: function (feature) {
                    return {
                        color: "#555",
                        weight: 2
                    };
                },
                onEachFeature: function(feature, layer) {
                    var content = "Region: " + feature.properties.REGNAME;
                    content += "<br>No of projects: 0";                    
                    layer.bindPopup(content);
                }
             }).addTo(self.map);
        });
    },
    getColor: function(d) {
        return d > 20  ? '#E31A1C' :
               d > 10  ? '#FC4E2A' :
               d > 5   ? '#FD8D3C' :
               d > 3   ? '#FEB24C' :
               d > 0   ? '#FED976' :
                          '#555';
    },
    bindRegions: function() {
        var projects = this.collection.filterProjects();
        var self = this;
        _.each(this.regionLayers, function(layer) {
            if (layer && layer._layers && layer._layers[layer._leaflet_id-1]) {
                var feature = layer._layers[layer._leaflet_id-1].feature;
                var regionProjects = projects.filterProjectsByRegion(feature.properties.REGNAME);
                var content = "Region: " + feature.properties.REGNAME;
                content += "<br>No of projects:" + regionProjects.length;
                layer.bindPopup(content); 
                layer.setStyle({
                    color: self.getColor(regionProjects.length),
                    weight: 2
                });
                if(!layer.marker) {
                    layer.marker = L.marker(layer.getBounds().getCenter());
                }
                if(regionProjects.length>0) {
                    layer.marker.bindPopup(content);
                    self.map.addLayer(layer.marker);
                } else {
                    self.map.removeLayer(layer.marker);
                }
            }
        });
    },
    render: function() {
        this.map.setView([28.700121,80.535514]);
        this.map.setZoom(12);
        var projects = this.collection.filterProjects();
        projects.each(function(project) {
            district = project.get('Project Districts');
            district = project.get('Project Region');
        });
        this.bindRegions();
    }
});

var AppView = Backbone.View.extend({
    el: "#container",
    events: {
        "click #reset": "reset"
    },
    initialize: function() {

    },
    reset: function() {
        this.collection.resetSectors();
        this.collection.resetRegions();
        this.collection.trigger('renderAll');
    },
    render: function() {

    }
});





