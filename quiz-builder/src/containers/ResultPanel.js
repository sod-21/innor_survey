import React, { Component } from "react";
import { connect } from "react-redux";
import ResultPanel from "../components/ResultPanel";
import {dispatchChangeResult} from "../actions/result";

export class Controller extends Component {

  render() {
    return (
        <div class="result-container">
            <div class="results-wrapper">
                <ResultPanel {...this.props}/>
            </div>
        </div>
    )
  }

};

export const mapStateToProps = store => {
  return {
    result: store.result,
    questions: store.questions,
  };
};

export const mapDispatchToProps = dispatch => {
    return {
        changeResult: (e) => {         
            dispatch(dispatchChangeResult(e));
        }
    };
}
export default connect(mapStateToProps, mapDispatchToProps)(Controller);
