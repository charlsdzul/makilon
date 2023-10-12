import { Col, Row, Table } from "antd";
import { default as React, useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import CContainer from "../../Components/CContainer";
import api from "../../Utils/api";

const columns = [
	{
		title: "Name",
		dataIndex: "name",
		sorter: true,
		render: (name) => `${name.first} ${name.last}`,
		width: "20%",
	},
	{
		title: "Gender",
		dataIndex: "gender",
		filters: [
			{
				text: "Male",
				value: "male",
			},
			{
				text: "Female",
				value: "female",
			},
		],
		width: "20%",
	},
	{
		title: "Email",
		dataIndex: "email",
	},
];

const getRandomuserParams = (params) => ({
	results: params.pagination?.pageSize,
	page: params.pagination?.current,
	...params,
});

const MisVacantes = (props) => {
	const { t } = useTranslation(["MisVacante"]);

	const [data, setData] = useState();
	const [loading, setLoading] = useState(false);
	const [tableParams, setTableParams] = useState({
		pagination: {
			current: 1,
			pageSize: 10,
		},
	});

	const llenarGrid = async () => {
		setLoading(true);

		const url = "https://randomuser.me/api";
		const params = getRandomuserParams(tableParams);
		const response = await api
			.get({ url, params, external: true })
			.then((response) => response.data.results)
			.catch((error) => error.response);

		console.log(response);

		setData(response);
		setLoading(false);

		// fetch(`https://randomuser.me/api?${qs.stringify(getRandomuserParams(tableParams))}`)
		// 	.then((res) => res.json())
		// 	.then(({ results }) => {
		// 		setData(results);
		// 		setLoading(false);
		// 		setTableParams({
		// 			...tableParams,
		// 			pagination: {
		// 				...tableParams.pagination,
		// 				total: 200,
		// 				// 200 is mock data, you should read it from server
		// 				// total: data.totalCount,
		// 			},
		// 		});
		// 	});
	};

	const iniciarPrograma = async () => {
		llenarGrid();

		// const { data: puestos } = await obtenerCatalogo({ catalogo: "puestos" });
		// setSourcePuestos(puestos);
		// const { data: puestosEspecificos } = await obtenerCatalogo({ catalogo: "puestosEspecificos" });
		// setSourcePuestosEspecificos(puestosEspecificos);
	};

	useEffect(() => {
		iniciarPrograma();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	const handleTableChange = (pagination, filters, sorter) => {
		setTableParams({
			pagination,
			filters,
			...sorter,
		});

		// `dataSource` is useless since `pageSize` changed
		if (pagination.pageSize !== tableParams.pagination?.pageSize) {
			setData([]);
		}
	};

	return (
		<CContainer title={t("Vacante.lblAgregarVacante")}>
			<Row justify="center" style={{ marginTop: "5rem" }}>
				<Col xs={24} sm={24} md={17} lg={16} xl={14} xxl={14}>
					{/* <QueueAnim type="scale">
						<div key="Result"> */}
					<Table
						columns={columns}
						rowKey={(record) => record.login.uuid}
						dataSource={data}
						pagination={tableParams.pagination}
						loading={loading}
						onChange={handleTableChange}
					/>
					{/* </div>
					</QueueAnim> */}
				</Col>
			</Row>
		</CContainer>
	);
};

export default MisVacantes;
