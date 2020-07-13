import {CHANGE_RESULT, READ_RESULT} from "../constants/result";

export default function reducer(state = {
    conditions: [
        {
            "sum": "or",
            target: "Score",
            condition: "=",
            val: [
                0,
            ],
            redirect: "",
        }
    ]
}, action) {
    if (action.type == READ_RESULT) {
        if (action.payload)
            state = action.payload;

        return state;
    }

    if (action.type == CHANGE_RESULT) {
        let payload = action.payload;

        if (payload.type == "REMOVE") {
            let index = payload.index;
            let dstate = Object.assign({}, {
                conditions: state.conditions
            });
            
            
            dstate.conditions = [
                ...dstate.conditions.slice(0, index),
                ...dstate.conditions.slice(index + 1)
            ];

            return dstate;
        } else if (payload.type == "ADD") {
            let dstate = Object.assign({}, {
                conditions: state.conditions
            });

            dstate.conditions.push(
                {
                    "sum": "or",
                    target: "Score",
                    condition: "=",
                    val: [
                        0,0
                    ],
                    redirect: "",
                }
            );

            return dstate;
        } else if (payload.type == "CHANGE") {
            let index = payload.index;
            let dstate = Object.assign({}, {
                conditions: state.conditions
            });
            
            
            dstate.conditions = [
                ...dstate.conditions.slice(0, index),
                payload.result,
                ...dstate.conditions.slice(index + 1)
            ];

            return dstate;
        }

    }
    
    return state;
}