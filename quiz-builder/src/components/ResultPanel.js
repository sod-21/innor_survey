import React, { Component, Fragment } from "react";
import PropTypes from "prop-types";
import ConditionItem from "./Content/ConditionItem";

class ResultPanel extends Component {

    constructor(props) {
        super(props);    
    }

    onAdd(index) {
        this.props.changeResult(
            {
                type: "ADD",
                index: index
            }
        );
    }

    onDelete(index) {
        this.props.changeResult(
            {
                type: "REMOVE",
                index: index
            }
        );
    }

    onChangeResult(val) {
        this.props.result.conditions[0].redirect = val;
        this.props.changeResult({
            type: "CHANGE",
            index: 0,
            result: this.props.result.conditions[0]
        });
    }

    render() {
        const conditions = this.props.result.conditions;
        const con1 = conditions[0];

        return (
            <div class="conditions_wrapper">
                
                    {
                        conditions.map((val, index) => (
                            <div class="r_row" index={"result-condition-index" + index}>

                                <ConditionItem result={val} point={index} changeResult={this.props.changeResult} questions={this.props.questions}></ConditionItem>
                                    <div class="r_action">
                                        <span class="r_a_add" onClick={(e) => {this.onAdd(index);}}>
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11 22C4.92487 22 0 17.0751 0 11C0 4.92487 4.92487 0 11 0C17.0751 0 22 4.92487 22 11C22 17.0751 17.0751 22 11 22ZM11 20C15.9706 20 20 15.9706 20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20ZM16 10H12V6H10V10H6V12H10V16H12V12H16V10Z" fill="#DADADA"/>
                                        </svg>
                                        </span>

                                        <span class="r_a_delete" onClick={(e) => {this.onDelete(index);}}>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23ZM12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM7 11V13H17V11H7Z" fill="#DADADA"/>
                                            </svg>
                                        </span>
                                        
                                    </div>
                                </div>
                        ))
                    }                                    
                
                <div class="r_redirect">
                    <span>then redirect to</span>   
                    <input type="text" name="r_redirect_url" class="q-control" placeholder="Url"  onChange={(e) => {this.onChangeResult(e.target.value);}} value={con1.redirect} ></input>
                </div>

            </div>
        );
    }

}

ResultPanel.propTypes = {   
    result: PropTypes.object,
    changeResult: PropTypes.func,
    questions: PropTypes.object
};

export default ResultPanel;
