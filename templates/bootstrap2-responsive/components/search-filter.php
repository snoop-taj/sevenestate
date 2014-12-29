<div class="wrap-search">
    <div class="container">

        <ul id="search_option_4" class="menu-onmap tabbed-selector">
            {options_values_li_4}
            <li class="list-property-button"><a href="{myproperties_url}">{lang_Listproperty}</a></li>
        </ul>
        
        <div class="search-form">
            <form class="form-inline">
                <input id="search_option_smart" type="text" class="span6" value="{search_query}" placeholder="{lang_CityorCounty}" />
                <select id="search_option_2" class="span3 selectpicker" placeholder="{options_name_2}">
                    {options_values_2}
                </select>
                <select id="search_option_3" class="span3 selectpicker nomargin" placeholder="{options_name_3}">
                    {options_values_3}
                </select>
                
                <div class="advanced-form-part hidden">
                <div class="form-row-space"></div>
                <input id="search_option_36_from" type="text" class="span3 mPrice" placeholder="{lang_Fromprice} ({options_prefix_36}{options_suffix_36})" />
                <input id="search_option_36_to" type="text" class="span3 xPrice" placeholder="{lang_Toprice} ({options_prefix_36}{options_suffix_36})" />
                <input id="search_option_19" type="text" class="span3 Bathrooms" placeholder="{options_name_19}" />
                <input id="search_option_20" type="text" class="span3" placeholder="{options_name_20}" />
                <div class="form-row-space"></div>
                <?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
                <input id="booking_date_from" type="text" class="span3 mPrice" placeholder="{lang_Fromdate}" />
                <input id="booking_date_to" type="text" class="span3 xPrice" placeholder="{lang_Todate}" />
                <div class="form-row-space"></div>
                <?php endif; ?>
                <label class="checkbox">
                <input id="search_option_11" type="checkbox" class="span1" value="true{options_name_11}" />{options_name_11}
                </label>
                <label class="checkbox">
                <input id="search_option_22" type="checkbox" class="span1" value="true{options_name_22}" />{options_name_22}
                </label>
                <label class="checkbox">
                <input id="search_option_25" type="checkbox" class="span1" value="true{options_name_25}" />{options_name_25}
                </label>
                <label class="checkbox">
                <input id="search_option_27" type="checkbox" class="span1" value="true{options_name_27}" />{options_name_27}
                </label>
                <label class="checkbox">
                <input id="search_option_28" type="checkbox" class="span1" value="true{options_name_28}" />{options_name_28}
                </label>
                <label class="checkbox">
                <input id="search_option_29" type="checkbox" class="span1" value="true{options_name_29}" />{options_name_29}
                </label>
                <label class="checkbox">
                <input id="search_option_32" type="checkbox" class="span1" value="true{options_name_32}" />{options_name_32}
                </label>
                <label class="checkbox">
                <input id="search_option_30" type="checkbox" class="span1" value="true{options_name_30}" />{options_name_30}
                </label>
                <label class="checkbox">
                <input id="search_option_33" type="checkbox" class="span1" value="true{options_name_33}" />{options_name_33}
                </label>
                <label class="checkbox">
                <input id="search_option_23" type="checkbox" class="span1" value="true{options_name_23}" />{options_name_23}
                </label>
                </div>
                <br style="clear:both;" />
                <div id="tags-filters">
    
                </div>
                
                
                <button id="search-start" type="submit" class="btn btn-info btn-large">&nbsp;&nbsp;{lang_Search}&nbsp;&nbsp;</button>
                <a id="search-start-map" href="#wrap-map" class="scroll"><button type="button" class="btn btn-success btn-large">{lang_ShowOnMap}</button></a>
                
                <img id="ajax-indicator-1" src="assets/img/ajax-loader.gif" />
            </form>
        </div>
    </div>
</div>



