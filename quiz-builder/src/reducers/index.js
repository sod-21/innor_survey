import { combineReducers } from "redux";

import quiz from "./quiz";
import questions from "./questions";
import activate from "./activeQuestion";
import loading from "./loading";
import result from "./result";
import mailer from "./integrate";
import settings from "./settings";
import help from "./help";
import options from "./options";
import quill from "./quill";

export default combineReducers({ loading, activate, quiz, questions, result, mailer, settings, help, options, quill });
